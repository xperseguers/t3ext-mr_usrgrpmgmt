<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010-2015 Xavier Perseguers <xavier@causal.ch>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
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
***************************************************************/

/**
 * TCA helper for extension mr_usrgrpmgmt.
 *
 * @category    TCA
 * @package     TYPO3
 * @subpackage  tx_mrusrgrpmgmt
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   2010-2015 Causal Sàrl
 * @license     http://www.gnu.org/copyleft/gpl.html
 */
class tx_mrusrgrpmgmt_itemfunctions {

	/**
	 * Default constructor.
	 */
	public function __construct() {
		if (!class_exists('recordList')) {
			require_once(PATH_typo3 . 'class.db_list.inc');
		}
	}

	/**
	 * Prepares the list of frontend users.
	 *
	 * @param array $params
	 * @param object $pObj
	 */
	public function users(array &$params, $pObj) {
		if (t3lib_div::inList('be_groups,fe_groups', $params['table'])) {
			$userTable = ($params['table'] === 'be_groups' ? 'be_users' : 'fe_users');
			$recordList = t3lib_div::makeInstance('recordList');
			$recordList->start(0, $userTable, 0);
			$queryParts = $recordList->makeQueryArray($userTable, 0);
			$queryParts['WHERE'] = '1=1' . t3lib_BEfunc::deleteClause($userTable);

			$result = $GLOBALS['TYPO3_DB']->exec_SELECT_queryArray($queryParts);
			while (($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)) !== FALSE) {
				$label = t3lib_BEfunc::getRecordTitle($userTable, $row);
				$params['items'][] = array($label, $row['uid']);
			}
			$GLOBALS['TYPO3_DB']->sql_free_result($result);
		}
	}
}


if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/mr_usrgrpmgmt/classes/class.tx_mrusrgrpmgmt_itemfunctions.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/mr_usrgrpmgmt/classes/class.tx_mrusrgrpmgmt_itemfunctions.php']);
}
