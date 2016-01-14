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
 * TCA helper for extension mr_usrgrpmgmt.
 *
 * @category    TCA
 * @package     TYPO3
 * @subpackage  tx_mrusrgrpmgmt
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   2010-2016 Causal Sàrl
 * @license     http://www.gnu.org/copyleft/gpl.html
 */
class tx_mrusrgrpmgmt_itemfunctions {

	/**
	 * Prepares the list of frontend users.
	 *
	 * @param array $params
	 * @param object $pObj
	 */
	public function users(array &$params, $pObj) {
		if (\TYPO3\CMS\Core\Utility\GeneralUtility::inList('be_groups,fe_groups', $params['table'])) {
			$userTable = ($params['table'] === 'be_groups' ? 'be_users' : 'fe_users');
			$recordList = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('recordList');
			$recordList->start(0, $userTable, 0);
			$queryParts = $recordList->makeQueryArray($userTable, 0);
			$queryParts['WHERE'] = '1=1' . \TYPO3\CMS\Backend\Utility\BackendUtility::deleteClause($userTable);

			$result = $GLOBALS['TYPO3_DB']->exec_SELECT_queryArray($queryParts);
			while (($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)) !== FALSE) {
				$label = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecordTitle($userTable, $row);
				$params['items'][] = array($label, $row['uid']);
			}
			$GLOBALS['TYPO3_DB']->sql_free_result($result);
		}
	}
}
