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

use LMS3\Docuseal\Service\LogService;
use LMS3\Docuseal\Service\SignatureService;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Http\RedirectResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * @author (c) 2024 Kallol Chakraborty <kchakraborty@learntube.de>
 */
class FormController extends ActionController
{
    protected SignatureService $signatureService;

    protected array $extConfig;

    protected bool $enableLogging;

    protected bool $enableSignature;

    protected bool $useCdnJs;

    public function __construct(SignatureService $signatureService)
    {
        $configObj = GeneralUtility::makeInstance(ExtensionConfiguration::class);
        $this->extConfig = $configObj->get('docuseal');

        $this->enableLogging = (bool) $this->extConfig['enableLogging'];
        $this->enableSignature = (bool) $this->extConfig['enableSignature'];
        $this->useCdnJs = (bool) $this->extConfig['useCdnJs'];

        if ($this->enableSignature) {
            foreach ($this->extConfig as $configKey=>$configValue) {
                if (in_array($configKey, ['enableLogging', 'useCdnJs'])) {
                    // Optional Settings
                    continue;
                }

                if (empty($configValue)) {
                    // Mandatory Settings
                    throw new RuntimeException('Incomplete setup. Missing configuration: '. $configKey);
                }
            }
        }

        $this->signatureService = $signatureService;
    }

    public function signAction(): ResponseInterface
    {
        // DocuSeal Integration
        $docuseal = [];
        if ($this->enableSignature) {
            $docusealBasePoint = trim($this->extConfig['docusealBasePoint']);
            $docusealToken = trim($this->extConfig['docusealToken']);

            // @TODO
        }

        $this->view->assign('docuseal', $docuseal);

        return $this->htmlResponse();
    }
}
