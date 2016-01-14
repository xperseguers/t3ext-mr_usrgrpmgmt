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

namespace Causal\MrUsrgrpmgmt;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;

/**
 * Hook to pre-process a single field in \TYPO3\CMS\Backend\Form\FormEngine.
 *
 * @category    Hooks
 * @package     TYPO3
 * @subpackage  tx_mrusrgrpmgmt
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   2010-2016 Causal Sàrl
 * @license     http://www.gnu.org/copyleft/gpl.html
 */
class FormEngine
{

    /**
     * Pre-processes the tceform rendering to specify currently assigned users.
     *
     * @param string $table
     * @param string $field
     * @param array $row
     * @param array $PA
     * @return void
     */
    public function getSingleField_beforeRender($table, $field, array $row, array &$PA)
    {
        if (GeneralUtility::inList('be_groups,fe_groups', $table) && $field === 'tx_mrusrgrpmgmt_users') {
            $users = $this->getAssignedUsers($table, $row['uid']);
            $list = array();
            foreach ($users as $user) {
                $list[] = $user['uid'];
            }
            $PA['itemFormElValue'] = implode(',', $list);
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
