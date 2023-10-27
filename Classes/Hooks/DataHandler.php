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

namespace Causal\MrUsrgrpmgmt\Hooks;

use Causal\MrUsrgrpmgmt\Traits\AssignedUsersTrait;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;

/**
 * Hook to pre-process a single field in \TYPO3\CMS\Core\DataHandling\DataHandler.
 *
 * @category    Hooks
 * @package     TYPO3
 * @subpackage  tx_mrusrgrpmgmt
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   2010-2023 Causal Sàrl
 * @license     https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class DataHandler
{
    use AssignedUsersTrait;

    /**
     * Updates the group assignment to corresponding user records.
     *
     * @param array $incomingFieldArray
     * @param string $table
     * @param int|string $id
     * @param \TYPO3\CMS\Core\DataHandling\DataHandler $pObj
     * @return void
     */
    public function processDatamap_preProcessFieldArray(array &$incomingFieldArray, $table, $id, \TYPO3\CMS\Core\DataHandling\DataHandler $pObj)
    {
        if (!in_array($table, ['be_groups', 'fe_groups'], true)) {
            return;
        }

        $userTable = ($table === 'be_groups' ? 'be_users' : 'fe_users');
        $users = $this->getAssignedUsers($table, $id);
        $oldList = [];
        foreach ($users as $user) {
            $oldList[] = $user['uid'];
        }
        $newList = GeneralUtility::intExplode(',', $incomingFieldArray['tx_mrusrgrpmgmt_users'], true);
        $removedUids = array_diff($oldList, $newList);
        $addedUids = array_diff($newList, $oldList);

        // Remove users that are not member anymore of the group
        foreach ($removedUids as $userUid) {
            if (!$userUid) {
                continue;
            }
            $user = BackendUtility::getRecord($userTable, $userUid);
            $usergroups = GeneralUtility::intExplode(',', $user['usergroup'], true);
            $key = array_search($id, $usergroups);
            unset($usergroups[$key]);

            GeneralUtility::makeInstance(ConnectionPool::class)
                ->getConnectionForTable($userTable)
                ->update(
                    $userTable,
                    [
                        'usergroup' => implode(',', $usergroups),
                    ],
                    [
                        'uid' => $userUid,
                    ]
                );
        }

        // Add users that are now member of the group
        foreach ($addedUids as $userUid) {
            if (!$userUid) {
                continue;
            }
            // New member is coming from suggest field
            if (\PHP_VERSION_ID >= 80000) {
                $newMemberComingFromSuggestField = str_starts_with((string)$userUid, $userTable . '_');
            } else {
                $newMemberComingFromSuggestField = GeneralUtility::isFirstPartOfStr($userUid, $userTable . '_');
            }
            if ($newMemberComingFromSuggestField) {
                $userUid = (int)substr((string)$userUid, strlen($userTable . '_'));
            }
            $user = BackendUtility::getRecord($userTable, $userUid);
            $usergroups = GeneralUtility::intExplode(',', $user['usergroup'], true);
            $usergroups[] = $id;

            GeneralUtility::makeInstance(ConnectionPool::class)
                ->getConnectionForTable($userTable)
                ->update(
                    $userTable,
                    [
                        'usergroup' => implode(',', $usergroups),
                    ],
                    [
                        'uid' => $userUid,
                    ]
                );
        }

        // Remove virtual user column to prevent TYPO3 from
        // trying to save content to this non-existing column
        unset($incomingFieldArray['tx_mrusrgrpmgmt_users']);
    }
}
