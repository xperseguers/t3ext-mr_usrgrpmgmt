<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Xavier Perseguers <typo3@perseguers.ch>
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
 * @author      Xavier Perseguers <typo3@perseguers.ch>
 * @package     TYPO3
 * @subpackage  mr_usrgrpmgmt
 * @version     SVN: $Id$
 */
class tx_mrusrgrpmgmt_itemfunctions {

	/**
	 * Prepares the list of frontend users.
	 *
	 * @param array $params
	 * @param object $pObj
	 */
	public function users(array &$params, $pObj) {
		switch ($params['table']) {
			case 'fe_groups':
				$users = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
					'*',
					'fe_users',
					'1=1' . t3lib_BEfunc::deleteClause('fe_users')
				);
				foreach ($users as $user) {
					$params['items'][] = array($user['username'], $user['uid']);
				}
				break;
			case 'be_groups':
				$users = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
					'*',
					'be_users',
					'1=1' . t3lib_BEfunc::deleteClause('be_users')
				);
				foreach ($users as $user) {
					$params['items'][] = array($user['username'], $user['uid']);
				}
				break;
		}
	}
}

?>