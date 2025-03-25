<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'LMS3 DocuSeal',
    'description' => 'Allows TYPO3 to integrate with DocuSeal services, enabling frontend users to securely sign documents using the DocuSeal API',
    'category' => 'plugin',
    'author' => 'Kallol Chakraborty (LEARNTUBE GmbH)',
    'author_email' => 'kchakraborty@learntube.de',
    'author_company' => 'LEARNTUBE GmbH',
    'state' => 'stable',
    'version' => '2.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.0-13.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
