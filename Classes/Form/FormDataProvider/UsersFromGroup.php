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

namespace Causal\MrUsrgrpmgmt\Form\FormDataProvider;

use TYPO3\CMS\Backend\Form\FormDataProviderInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;

/**
 * Additional data when editing a record.
 *
 * @category    Form\FormDataProvider
 * @package     TYPO3
 * @subpackage  tx_mrusrgrpmgmt
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   2016 Causal Sàrl
 * @license     http://www.gnu.org/copyleft/gpl.html
 */
class UsersFromGroup implements FormDataProviderInterface
{

    /**
     * Fetches users from a given group record.
     *
     * @param array $result
     * @return array
     */
    public function addData(array $result)
    {
        if (GeneralUtility::inList('be_groups,fe_groups', $result['tableName'])) {
            $users = $this->getAssignedUsers($result['tableName'], $result['databaseRow']['uid']);
            $result['databaseRow']['tx_mrusrgrpmgmt_users'] = [];
            foreach ($users as $user) {
                $result['databaseRow']['tx_mrusrgrpmgmt_users'][] = $user['uid'];
            }
        }

        return $result;
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
