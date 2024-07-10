<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'DocuSeal for TYPO3',
    'description' => 'Integrates DocuSeal in TYPO3 CMS',
    'category' => 'plugin',
    'author' => 'Kallol Chakraborty',
    'author_email' => 'kchakraborty@learntube.de',
    'author_company' => 'Learntube! GmbH',
    'state' => 'stable',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.0.0-12.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
