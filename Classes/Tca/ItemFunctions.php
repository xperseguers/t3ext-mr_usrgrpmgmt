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

namespace Causal\MrUsrgrpmgmt\Tca;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;

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
class ItemFunctions {

	/**
	 * Prepares the list of frontend users.
	 *
	 * @param array $params
	 * @param object $pObj
	 */
	public function users(array &$params, $pObj) {
		if (GeneralUtility::inList('be_groups,fe_groups', $params['table'])) {
			$databaseConnection = $this->getDatabaseConnection();
			$userTable = ($params['table'] === 'be_groups' ? 'be_users' : 'fe_users');
			/** @var \TYPO3\CMS\Recordlist\RecordList\AbstractDatabaseRecordList $recordList */
			$recordList = GeneralUtility::makeInstance('TYPO3\\CMS\\Recordlist\\RecordList\\AbstractDatabaseRecordList');
			$recordList->start(0, $userTable, 0);
			$queryParts = $recordList->makeQueryArray($userTable, 0);
			$queryParts['WHERE'] = '1=1' . BackendUtility::deleteClause($userTable);

			$result = $databaseConnection->exec_SELECT_queryArray($queryParts);
			while (($row = $databaseConnection->sql_fetch_assoc($result)) !== FALSE) {
				$label = BackendUtility::getRecordTitle($userTable, $row);
				$params['items'][] = array($label, $row['uid']);
			}
			$databaseConnection->sql_free_result($result);
		}
	}

	/**
	 * Returns the database connection.
	 *
	 * @return \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	protected function getDatabaseConnection() {
		return $GLOBALS['TYPO3_DB'];
	}

}
