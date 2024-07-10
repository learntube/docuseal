<?php

declare(strict_types=1);

namespace LMS3\Docuseal\Service;

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

use TYPO3\CMS\Core\Core\Environment;

/**
 * @author (c) 2024 Kallol Chakraborty <kchakraborty@learntube.de>
 */
class LogService
{
    public static function savetLogToFile(string $message, string $indentifier): void
    {
        $logDir = Environment::getPublicPath().'/../var/log/'.$indentifier.'/';
        if (!file_exists($logDir)) {
            mkdir($logDir, 0777, true);
        }

        $logFile = $logDir.'log_' . date('d-M-Y') . '.txt';
        file_put_contents($logFile, $message . "\n", FILE_APPEND);
    }
}
