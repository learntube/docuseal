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

defined('TYPO3') || die();

use LMS3\Docuseal\Controller\FormController;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
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
        FormController::class => ['sign', 'update'],
    ],
    [
        FormController::class => ['sign', 'update'],
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);
