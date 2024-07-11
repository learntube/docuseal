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

use LMS3\Docuseal\Service\LogService;

/**
 * @author (c) 2024 Kallol Chakraborty <kchakraborty@learntube.de>
 */
class DocusealService
{
    protected string $identifier = 'docuseal';

    public function getResponse(
        string $uri,
        string $token,
        string $method = 'GET',
        array $data = [],
        bool $jsonDecode = true
    ): string|array|bool
    {
        // Send cURL request
        $response = $this->sendCurlRequest($uri, $token, $method, $data);
        $responseAsArray = json_decode($response, true);

        // Handle exceptions
        if (array_key_exists('error', $responseAsArray)) {
            LogService::savetLogToFile('Remote Exception: Failed to send cURL request for URL - ' . $uri . ' Details: ' . $responseAsArray['error'], $this->identifier);
            return false;
        } else if (empty($responseAsArray)) {
            LogService::savetLogToFile('Remote Exception: Failed to send cURL request for URL - ' . $uri . ' Details: ' . 'Not found', $this->identifier);
            return false;
        }

        // Handle successful response
        if ($jsonDecode) {
            return $responseAsArray;
        } else {
            return $response;
        }
    }

    protected function sendCurlRequest(string $uri, string $token, string $method, array $data): string|bool
    {
        // Prepare the headers
        $headers = [
            'X-Auth-Token: ' . $token,
            'Content-Type: application/json'
        ];

        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt_array($ch, [
            CURLOPT_URL => $uri,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => !empty($data) ? json_encode($data) : null,
        ]);

        // Execute cURL request
        $response = curl_exec($ch);

        // Check for errors
        if ($response === false) {
            $errorMessage = curl_error($ch);
            LogService::savetLogToFile('cURL Error: ' . $errorMessage . ' for URL - ' . $uri, $this->identifier);
        }

        // Close cURL session
        curl_close($ch);

        return $response;
    }
}
