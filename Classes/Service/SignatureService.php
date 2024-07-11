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

use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @author (c) 2024 Kallol Chakraborty <kchakraborty@learntube.de>
 */
class SignatureService
{
    protected Context $context;

    protected Connection $connection;

    protected int $uUid;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;

        $this->context = GeneralUtility::makeInstance(Context::class);
        $this->uUid = intval($this->context->getPropertyFromAspect('frontend.user', 'id'));
    }

    public function create(array $data): void
    {
        $user = $this->queryUser();
        $uPid = $user['pid'];
        $uSignatures = (int) $user['docuseal_signatures'];

        $dql = <<<DQL
            INSERT IGNORE INTO
                tx_docuseal_domain_model_signatures(pid, crdate, tstamp, fe_user, template_id, submitter_slug)
            VALUES
                (:pid, :crdate, :tstamp, :user, :template_id, :submitter_slug)
        DQL;

        try {
            // Insert Signature Record
            $this->connection->executeStatement(
                $dql,
                [
                    'pid' => $uPid,
                    'crdate' => $GLOBALS['EXEC_TIME'],
                    'tstamp' => $GLOBALS['EXEC_TIME'],
                    'user' => $this->uUid,
                    'template_id' => $data['template_id'],
                    'submitter_slug' => $data['submitter_slug']
                ],
                [
                    'pid' => Connection::PARAM_INT,
                    'crdate' => Connection::PARAM_INT,
                    'tstamp' => Connection::PARAM_INT,
                    'user' => Connection::PARAM_INT,
                    'template_id' => Connection::PARAM_STR,
                    'submitter_slug' => Connection::PARAM_STR
                ]
            );

            // Update User Record
            $this->updateUser('docuseal_signatures', $uSignatures + 1);
        } catch (\Exception $e) {}
    }

    public function queryUserSignatures(int $templateId): array
    {
        $sql = <<<SQL
            SELECT
                uid,
                pid,
                template_id,
                submitter_slug,
                signed_pdf_link
            FROM
                tx_docuseal_domain_model_signatures
            WHERE
                fe_user = :user AND
                template_id = :template_id AND
                hidden = 0 AND
                deleted = 0
            LIMIT
                1
        SQL;

        $signatures = $this->connection
            ->executeQuery(
                $sql,
                [
                    'user' => $this->uUid,
                    'template_id' => $templateId
                ],
                [
                    'user' => Connection::PARAM_INT,
                    'template_id' => Connection::PARAM_INT
                ]
            )
            ->fetchAssociative();

        return $signatures ? $signatures : [];
    }

    public function queryUser(): array|bool
    {
        $sql = <<<SQL
            SELECT
                *
            FROM
                fe_users
            WHERE
                uid = :user AND
                disable = 0 AND
                deleted = 0
            LIMIT
                1
        SQL;

        $user = $this->connection
            ->executeQuery(
                $sql,
                [
                    'user' => $this->uUid
                ],
                [
                    'user' => Connection::PARAM_INT
                ]
            )
            ->fetchAssociative();

        return $user ? $user : [];
    }

    public function updateUser(string $property, mixed $value): void
    {
        $dql = <<<DQL
            UPDATE
                fe_users
            SET
                $property = :value
            WHERE
                uid = :uid
        DQL;

        try {
            $this->connection
                ->executeStatement(
                    $dql,
                    [
                        'uid' => $this->uUid,
                        'value' => $value
                    ],
                    [
                        'uid' => Connection::PARAM_INT,
                        'value' => Connection::PARAM_STR
                    ]
                );
        } catch (\Exception $e) {}
    }

    public function updateSignature(int $uid, string $property, mixed $value): void
    {
        $dql = <<<DQL
            UPDATE
                tx_docuseal_domain_model_signatures
            SET
                $property = :value
            WHERE
                uid = :uid
        DQL;

        try {
            $this->connection
                ->executeStatement(
                    $dql,
                    [
                        'uid' => $uid,
                        'value' => $value
                    ],
                    [
                        'uid' => Connection::PARAM_INT,
                        'value' => Connection::PARAM_STR
                    ]
                );
        } catch (\Exception $e) {}
    }
}
