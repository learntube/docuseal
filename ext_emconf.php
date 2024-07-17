<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'LMS3 DocuSeal',
    'description' => 'Allows TYPO3 to integrate with DocuSeal services, enabling frontend users to securely sign documents using the DocuSeal API',
    'category' => 'plugin',
    'author' => 'Kallol Chakraborty (Learntube GmbH)',
    'author_email' => 'kchakraborty@learntube.de',
    'author_company' => 'Learntube GmbH',
    'state' => 'stable',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-12.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
