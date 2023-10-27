<?php
declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace Causal\MrUsrgrpmgmt\Traits;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

trait AssignedUsersTrait
{
    /**
     * Returns the users assigned to a given group.
     *
     * @param string $table
     * @param int $groupUid
     * @return array
     */
    protected function getAssignedUsers(string $table, int $groupUid): array
    {
        $userTable = ($table === 'be_groups' ? 'be_users' : 'fe_users');
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($table);

        $queryBuilder
            ->select('uid')
            ->from($table)
            ->where(
                'CONCAT(CONCAT(\',\',' . $queryBuilder->quoteIdentifier('usergroup') . '), \',\') LIKE \'%,' . $groupUid . ',%\''
            )
            ->orderBy('username')
            ->execute()
            ->fetchAllAssociative();

        return $users;
    }
}
