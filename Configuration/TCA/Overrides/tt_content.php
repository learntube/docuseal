<?php

declare(strict_types=1);

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

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

// Register Plugin
$contentTypeName = ExtensionUtility::registerPlugin(
    'Docuseal',
    'Pi1',
    'LLL:EXT:docuseal/Resources/Private/Language/locallang_db.xlf:tx_docuseal_pi1.name',
    'tx-docuseal-svgicon',
    'DocuSeal',
    'LLL:EXT:docuseal/Resources/Private/Language/locallang_db.xlf:tx_docuseal_pi1.description',
);

// Add the FlexForm
ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:docuseal/Configuration/FlexForms/Sign.xml',
    $contentTypeName
);

// Add the FlexForm to the show item list
ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:plugin, pi_flexform',
    $contentTypeName,
    'after:palette:headers'
);
