<?php

declare(strict_types = 1);

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

defined('TYPO3') || die();

use LMS3\Docuseal\Controller\FormController;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionManagementUtility::addTypoScriptConstants(
    "@import 'EXT:docuseal/Configuration/TypoScript/constants.typoscript'"
);

ExtensionManagementUtility::addTypoScriptSetup(
    "@import 'EXT:docuseal/Configuration/TypoScript/setup.typoscript'"
);

ExtensionUtility::configurePlugin(
    'Docuseal',
    'Pi1',
    [
        FormController::class => 'sign',
    ],
    [
        FormController::class => 'sign',
    ]
);

ExtensionManagementUtility::addPageTSConfig(
    'mod {
        wizards.newContentElement.wizardItems.plugins {
            elements {
                docuseal {
                    iconIdentifier = docuseal-plugin-pi1
                    title = LLL:EXT:docuseal/Resources/Private/Language/locallang_db.xlf:tx_docuseal_pi1.name
                    description = LLL:EXT:docuseal/Resources/Private/Language/locallang_db.xlf:tx_docuseal_pi1.description
                    tt_content_defValues {
                        CType = list
                        list_type = docuseal_pi1
                    }
                }
            }
            show = *
        }
   }'
);

$iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
$iconRegistry->registerIcon(
    'docuseal-plugin-pi1',
    SvgIconProvider::class,
    ['source' => 'EXT:docuseal/Resources/Public/Icons/Extension.svg']
);
