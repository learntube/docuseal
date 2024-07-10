<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:docuseal/Resources/Private/Language/locallang_db.xlf:tx_docuseal_domain_model_signatures',
        'label' => 'signed_pdf_link',
        'label_alt' => 'template_id',
        'label_alt_force' => true,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'hideTable' => 1,
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
        'searchFields' => 'signed_pdf_link',
        'iconfile' => 'EXT:docuseal/Resources/Public/Icons/Extension.svg'
    ],
    'types' => [
        '1' => ['showitem' => 'template_id, signed_pdf_link, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden'],
    ],
    'columns' => [
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => '',
                        'invertStateDisplay' => true,
                    ],
                ],
            ],
        ],

        'template_id' => [
            'exclude' => true,
            'label' => 'LLL:EXT:docuseal/Resources/Private/Language/locallang_db.xlf:tx_docuseal_domain_model_signatures.template_id',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => '',
                'readOnly' => true,
            ],
        ],
        'signed_pdf_link' => [
            'exclude' => true,
            'label' => 'LLL:EXT:docuseal/Resources/Private/Language/locallang_db.xlf:tx_docuseal_domain_model_signatures.signed_pdf_link',
            'config' => [
                'type' => 'link',
                'size' => 60,
                'allowedTypes' => ['url'],
                'readOnly' => true,
            ]
        ],

        'fe_user' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
];
