<?php

declare(strict_types=1);

namespace LMS3\Docuseal\Controller;

/* * *************************************************************
 *
 *  Copyright notice
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

use LMS3\Docuseal\Service\DocusealService;
use LMS3\Docuseal\Service\SignatureService;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Http\RedirectResponse;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * @author (c) 2024 Kallol Chakraborty <kchakraborty@learntube.de>
 */
class FormController extends ActionController
{
    const EXTENSION_NAME = 'docuseal';

    const TRANSLATE_FILE = 'LLL:EXT:docuseal/Resources/Private/Language/locallang.xlf';

    protected DocusealService $docusealService;

    protected SignatureService $signatureService;

    protected array $extConfig;

    protected bool $enableSignature;

    protected int $page;

    protected array $user;

    public function __construct(
        DocusealService $docusealService,
        SignatureService $signatureService
    )
    {
        // Initialize the configuration object to retrieve extension settings
        $configObj = GeneralUtility::makeInstance(ExtensionConfiguration::class);
        $this->extConfig = $configObj->get('docuseal');

        // Set class properties based on the retrieved configuration settings
        $this->enableSignature = (bool) $this->extConfig['enableSignature'];

        // Get the current page ID from the global TYPO3 frontend object
        $this->page = intval($GLOBALS['TSFE']->id);

        // Assign the injected services to class properties
        $this->docusealService = $docusealService;
        $this->signatureService = $signatureService;
    }

    public function signAction(): ResponseInterface
    {
        $docuseal = [];
        $errorFlag = 0;

        // Check if the signature feature is enabled
        if ($this->enableSignature) {
            // Validate the essential configuration settings
            foreach ($this->extConfig as $configKey => $configValue) {
                if (empty($configValue)) {
                    // Add an error message if a mandatory setting is missing
                    $this->addFlashMessage(
                        $this->translate('msg.text.missing_config') . ' ' . $configKey,
                        $this->translate('msg.heading.missing_config'),
                        ContextualFeedbackSeverity::ERROR
                    );
                    $errorFlag++;
                }
            }

            // Retrieve information about the currently logged-in user
            $this->user = $this->signatureService->queryUser();

            if ($errorFlag == 0 && !empty($this->user)) {
                // Extract DocuSeal credentials from the configuration
                $docusealBasePoint = trim($this->extConfig['docusealBasePoint']);
                $docusealToken = trim($this->extConfig['docusealToken']);

                // Create or fetch the unique DocuSeal ID for the user
                if (empty($this->user['docuseal_id'])) {
                    $docusealId = substr(str_shuffle(uniqid()), 0, 10) . '-' . mt_rand(1111, 9999);
                    $this->signatureService->updateUser('docuseal_id', $docusealId);
                } else {
                    $docusealId = $this->user['docuseal_id'];
                }

                // Get the template ID from the settings
                $templateId = (int) $this->settings['templateId'];

                // Proceed if the template ID and user email are available
                if (!empty($templateId) && !empty($this->user['email'])) {
                    // Generate a unique external ID for the submission
                    $submitterExternalId = $docusealId . '-' . $templateId;

                    // Retrieve custom CSS settings, if any
                    $customCss = $this->settings['customCss'];

                    // Determine the URL to redirect to after submission completion
                    $redirectionLink = $this->getHref('Form', 'update', ['extId' => $submitterExternalId]);

                    // Fetch the template information from DocuSeal
                    $template = $this->docusealService->getResponse($docusealBasePoint . '/templates/' . $templateId, $docusealToken);

                    if (!empty($template)) {
                        // Check if a submission already exists for the user, otherwise create a new one
                        $submissions = $this->docusealService->getResponse($docusealBasePoint . '/submissions?template_id=' . $templateId . '&q=' . $this->user['email'], $docusealToken);

                        if (empty($submissions['data'])) {
                            $newSubmissionData = [
                                'template_id' => $templateId,
                                'send_email' => false,
                                'order' => 'preserved',
                                'submitters' => [
                                    [
                                        'role' => 'First Party',
                                        'email' => $this->user['email'],
                                        'external_id' => $submitterExternalId,
                                        'fields' => $this->getFieldMapping()
                                    ]
                                ]
                            ];

                            // Create a new submission
                            $submission = $this->docusealService->getResponse($docusealBasePoint . '/submissions', $docusealToken, 'POST', $newSubmissionData);
                            $submitterSlug = $submission[0]['slug'];

                            // Create a signature entry if it does not exist and update the user
                            $apiData = [];
                            $apiData['template_id'] = $templateId;
                            $apiData['submitter_slug'] = $submitterSlug;
                            if (count($this->signatureService->queryUserSignatures($templateId)) == 0) {
                                $this->signatureService->create($apiData);
                            }
                        } else {
                            $userSignature = $this->signatureService->queryUserSignatures($templateId);
                            $submitterSlug = $userSignature['submitter_slug'];
                        }

                        // Prepare the embedded signing form for DocuSeal
                        $docusealFormBasePoint = trim($this->extConfig['docusealFormBasePoint']);
                        $pdfDocusealSignLink = rtrim($docusealFormBasePoint, '/') . '/s/' . $submitterSlug;
                        $docuseal = [
                            'dataSrc' => $pdfDocusealSignLink,
                            'dataEmail' => $this->user['email'],
                            'redirectionLink' => $redirectionLink,
                            'externalId' => $submitterExternalId,
                            'customCss' => $customCss ? $customCss : '',
                            'language' => $this->getLanguage()
                        ];
                    }
                }
            }
        }

        // Assign the prepared data to the view
        $this->view->assign('docuseal', $docuseal);

        // Return the HTML response
        return $this->htmlResponse();
    }

    public function updateAction(): RedirectResponse
    {
        // Check if the required 'extId' argument is present in the request
        if (!$this->request->hasArgument('extId')) {
            throw new \Exception('Bad Request');
        }

        // Gather needed information
        $redirectionLink = '';
        $templateId = (int) $this->settings['templateId']; // Retrieve template ID from settings
        $submitterExternalId = $this->request->getArgument('extId'); // Get external ID from the request
        $userSignature = $this->signatureService->queryUserSignatures($templateId); // Query user signatures based on template ID

        // Perform update
        if ($this->enableSignature && empty($userSignature['signed_pdf_link'])) {
            // Retrieve DocuSeal credentials from the configuration
            $docusealBasePoint = trim($this->extConfig['docusealBasePoint']);
            $docusealToken = trim($this->extConfig['docusealToken']);

            // Retrieve signed PDF URL from DocuSeal
            $sleepInterval = 3;
            $maxRetries = 3;

            // Wait for a few seconds to allow the Docuseal API to process the request
            sleep($sleepInterval);

            for ($attempt = 0; $attempt < $maxRetries; $attempt++) {
                $submitters = $this->docusealService->getResponse($docusealBasePoint . '/submitters?external_id=' . $submitterExternalId . '&limit=1', $docusealToken);
                if (!empty($submitters['data'])) {
                    $submitter = $submitters['data'][0];
                    if (!empty($submitter['documents'])) {
                        if ($submitter['status'] === 'completed') {
                            // Obtain the signed PDF URL and save it to the database
                            $signedPDFDownloadLink = $submitter['documents'][0]['url'];
                            $this->signatureService->updateSignature($userSignature['uid'], 'signed_pdf_link', $signedPDFDownloadLink);
                            break;
                        }
                    }
                }
                sleep($sleepInterval);
            }

            if ($attempt === $maxRetries) {
                // Handle the case where the documents array did not fill up after the maximum number of retries
                throw new RuntimeException("Unable to retrieve the signed PDF URL after several attempts. Please try reloading the page or contact the administrator for assistance.");
            }

            // Determine URL to redirect to after the update
            if (!empty($this->settings['redirectAfterSign'])) {
                // Generate a TYPO3 URL for redirection after signing
                $cObj = GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer');
                $conf = [
                    'parameter' => $this->settings['redirectAfterSign'],
                    'useCashHash' => false,
                    'returnLast' => 'url',
                    'forceAbsoluteUrl' => true
                ];
                $redirectionLink = $cObj->typolink_URL($conf);
            }
        }

        // Perform redirection
        if (!empty($redirectionLink)) {
            // Redirect to the determined URL
            return new RedirectResponse($redirectionLink, 302);
        } else {
            // If no redirection link is set then redirect back to sign page
            $uri = $this->uriBuilder->reset()->setTargetPageUid($this->page)->build();
            return new RedirectResponse($uri, 302);
        }
    }

    private function getFieldMapping(): array
    {
        $fieldMapping = $this->settings['fieldMapping'];

        if (empty($fieldMapping)) {
            return [];
        }

        $lines = explode("\n", $fieldMapping);
        $mappingArray = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line) && strpos($line, ':') !== false) {
                list($feuserField, $docusealField) = explode(':', $line, 2);
                $feuserField = trim($feuserField);
                $docusealField = trim($docusealField);
                if (!empty($feuserField) && !empty($docusealField)) {
                    $mappingArray[$feuserField] = $docusealField;
                }
            }
        }

        $fields = [];

        foreach ($mappingArray as $feuserField => $docusealField) {
            $fields[] = [
                'name' => $docusealField,
                'default_value' => $this->user[$feuserField] ?? '',
                'readonly' => true
            ];
        }

        return $fields;
    }

    private function translate($fieldKey): string
    {
        $llKey = static::TRANSLATE_FILE . ':label.' . $fieldKey;
        return LocalizationUtility::translate($llKey, static::EXTENSION_NAME);
    }

    private function getHref(string $controller, string $action, array $parameters = []): string
    {
        return $this->uriBuilder->uriFor($action, $parameters, $controller);
    }

    private function getLanguage(): string
    {
        if (Environment::isCli()) {
            return "en";
        }

        $siteLanguage = $this->request->getAttribute('language');
        $language = $siteLanguage?->getLocale()->getLanguageCode();

        if (empty($language) || $language === 'default') {
            $language = 'en';
        }

        return $language;
    }
}
