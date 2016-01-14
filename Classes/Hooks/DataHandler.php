<?php
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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;

/**
 * Hook to pre-process a single field in \TYPO3\CMS\Core\DataHandling\DataHandler.
 *
 * @category    Hooks
 * @package     TYPO3
 * @subpackage  tx_mrusrgrpmgmt
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   2010-2016 Causal Sàrl
 * @license     http://www.gnu.org/copyleft/gpl.html
 */
class DataHandler
{

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
        if (GeneralUtility::inList('be_groups,fe_groups', $table)) {
            $userTable = ($table === 'be_groups' ? 'be_users' : 'fe_users');
            $users = $this->getAssignedUsers($table, $id);
            $oldList = array();
            foreach ($users as $user) {
                $oldList[] = $user['uid'];
            }
            $newList = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $incomingFieldArray['tx_mrusrgrpmgmt_users']);
            $removedUids = array_diff($oldList, $newList);
            $addedUids = array_diff($newList, $oldList);

            // Remove users that are not member anymore of the group
            foreach ($removedUids as $userUid) {
                if (!$userUid) {
                    continue;
                }
                $user = BackendUtility::getRecord($userTable, $userUid);
                $usergroups = GeneralUtility::trimExplode(',', $user['usergroup']);
                $key = array_search($id, $usergroups);
                unset($usergroups[$key]);

                $this->getDatabaseConnection()->exec_UPDATEquery(
                    $userTable,
                    'uid=' . $userUid,
                    array(
                        'usergroup' => implode(',', $usergroups),
                    )
                );
            }

            // Add users that are now member of the group
            foreach ($addedUids as $userUid) {
                if (!$userUid) {
                    continue;
                }
                if (GeneralUtility::isFirstPartOfStr($userUid, $userTable . '_')) {
                    // New member is coming from suggest field
                    $userUid = substr($userUid, strlen($userTable . '_'));
                }
                $user = BackendUtility::getRecord($userTable, $userUid);
                $usergroups = GeneralUtility::trimExplode(',', $user['usergroup']);
                $usergroups[] = $id;

                $this->getDatabaseConnection()->exec_UPDATEquery(
                    $userTable,
                    'uid=' . $userUid,
                    array(
                        'usergroup' => implode(',', $usergroups),
                    )
                );
            }

            // Remove virtual user column to prevent TYPO3 from
            // trying to save content to this non-existing column
            unset($incomingFieldArray['tx_mrusrgrpmgmt_users']);
        }
    }

    /**
     * Returns the users assigned to a given group.
     *
     * @param string $table
     * @param integer $groupUid
     * @return array
     */
    protected function getAssignedUsers($table, $groupUid)
    {
        $userTable = ($table === 'be_groups' ? 'be_users' : 'fe_users');
        $users = $this->getDatabaseConnection()->exec_SELECTgetRows(
            'uid',
            $userTable,
            'CONCAT(CONCAT(\',\', usergroup), \',\') LIKE \'%,' . $groupUid . ',%\'' .
            BackendUtility::deleteClause($userTable),
            '',
            'username'
        );

        return $users;
    }

    /**
     * Returns the database connection.
     *
     * @return \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }

}
