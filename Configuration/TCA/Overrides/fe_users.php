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

defined('TYPO3') or die();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$feusersColumns = [
    'docuseal_id' => [
        'exclude' => true,
        'label' => 'LLL:EXT:docuseal/Resources/Private/Language/locallang_db.xlf:fe_users.docuseal_id',
        'config' => [
            'type' => 'input',
            'size' => 30,
            'eval' => 'trim',
            'default' => '',
            'readOnly' => true,
        ],
    ],
    'docuseal_signatures' => [
        'exclude' => true,
        'label' => 'LLL:EXT:docuseal/Resources/Private/Language/locallang_db.xlf:fe_users.docuseal_signatures',
        'config' => [
            'type' => 'inline',
            'foreign_table' => 'tx_docuseal_domain_model_signatures',
            'foreign_field' => 'fe_user',
            'maxitems' => 9999,
            'appearance' => [
                'collapseAll' => 1,
                'levelLinksPosition' => 'top',
                'showSynchronizationLink' => 1,
                'showPossibleLocalizationRecords' => 1,
                'showAllLocalizationLink' => 1
            ],
            'readOnly' => true,
        ],
    ],
];

ExtensionManagementUtility::addTCAcolumns('fe_users', $feusersColumns);
ExtensionManagementUtility::addToAllTCAtypes(
    'fe_users',
    '--div--;LLL:EXT:docuseal/Resources/Private/Language/locallang_db.xlf:fe_users.div.docuseal,
        docuseal_id, docuseal_signatures'
);
