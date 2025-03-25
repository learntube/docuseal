<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

return [
    'tx-docuseal-svgicon' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:docuseal/Resources/Public/Icons/Extension.svg',
    ],
];
