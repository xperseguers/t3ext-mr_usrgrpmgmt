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

/**
 * Hook to pre-process a single field in tceforms and in tcemain.
 *
 * @category    Hooks
 * @package     TYPO3
 * @subpackage  tx_mrusrgrpmgmt
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   2010-2016 Causal Sàrl
 * @license     http://www.gnu.org/copyleft/gpl.html
 */
class tx_mrusrgrpmgmt_tce {

	protected $backupVirtualColumn;

	/**
	 * Pre-processes the tceform rendering to specify currently assigned users.
	 *
	 * @param string $table
	 * @param string $field
	 * @param array $row
	 * @param array $PA
	 * @return void
	 */
	public function getSingleField_beforeRender($table, $field, array $row, array &$PA) {
		if (\TYPO3\CMS\Core\Utility\GeneralUtility::inList('be_groups,fe_groups', $table) && $field === 'tx_mrusrgrpmgmt_users') {
			$users = $this->getAssignedUsers($table, $row['uid']);
			$list = array();
			foreach ($users as $user) {
				$list[] = $user['uid'];
			}
			$PA['itemFormElValue'] = implode(',', $list);
		}
	}

	/**
	 * Updates the group assignment to corresponding user records.
	 *
	 * @param array $incomingFieldArray
	 * @param string $table
	 * @param int|string $id
	 * @param \TYPO3\CMS\Core\DataHandling\DataHandler $pObj
	 * @return void
	 */
	public function processDatamap_preProcessFieldArray(array &$incomingFieldArray, $table, $id, \TYPO3\CMS\Core\DataHandling\DataHandler $pObj) {
		if (\TYPO3\CMS\Core\Utility\GeneralUtility::inList('be_groups,fe_groups', $table)) {
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
				$user = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord($userTable, $userUid);
				$usergroups = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $user['usergroup']);
				$key = array_search($id, $usergroups);
				unset($usergroups[$key]);

				$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
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
				if (\TYPO3\CMS\Core\Utility\GeneralUtility::isFirstPartOfStr($userUid, $userTable . '_')) {
					// New member is coming from suggest field
					$userUid = substr($userUid, strlen($userTable . '_'));
				}
				$user = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord($userTable, $userUid);
				$usergroups = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $user['usergroup']);
				$usergroups[] = $id;

				$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
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
	protected function getAssignedUsers($table, $groupUid) {
		$userTable = ($table === 'be_groups' ? 'be_users' : 'fe_users');
		$users = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			'uid',
			$userTable,
			'CONCAT(CONCAT(\',\', usergroup), \',\') LIKE \'%,' . $groupUid . ',%\'' .
				\TYPO3\CMS\Backend\Utility\BackendUtility::deleteClause($userTable),
			'',
			'username'
		);
		return $users;
	}

}
