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
 * Hook to preprocess a single field in tceforms and in tcemain.
 *
 * @author      Xavier Perseguers <typo3@perseguers.ch>
 * @package     TYPO3
 * @subpackage  mr_usrgrpmgmt
 * @version     SVN: $Id$
 */
class tx_mrusrgrpmgmt_tce {

	/**
	 * Preprocess the tceform rendering to specify currently assigned users.
	 *
	 * @param string $table
	 * @param string $field
	 * @param array $row
	 * @param array $PA
	 * @return void
	 */
	public function getSingleField_beforeRender($table, $field, $row, &$PA) {
		if (($table === 'fe_groups' || $table === 'be_groups') && $field === 'tx_mrusrgrpmgmt_users') {
			$users = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
				'uid',
				'fe_users',
				'CONCAT(CONCAT(\',\', usergroup), \',\') LIKE \'%,' . $row['uid'] . ',%\'' .
					t3lib_BEfunc::deleteClause('fe_users') 
			);
			$list = array();
			foreach ($users as $user) {
				$list[] = $user['uid'];
			}
			$PA['itemFormElValue'] = implode(',', $list);
		}
	}

	/**
	 * Processes the save of a fe_group/be_group record.
	 * 
	 * @param string $status
	 * @param string $table
	 * @param integer $id
	 * @param array $fieldArray
	 * @param t3lib_TCEmain $pObj
	 * @return void
	 */
	public function processDatamap_postProcessFieldArray ($status, $table, $id, &$fieldArray, t3lib_TCEmain $pObj) {
		switch ($table) {
			case 'fe_groups':
				t3lib_div::debug($fieldArray, $status);

					// Remove virtual user column
				unset($fieldArray['tx_mrusrgrpmgmt_users']);
				break;
			case 'be_groups':
				t3lib_div::debug($fieldArray, $status);

					// Remove virtual user column
				unset($fieldArray['tx_mrusrgrpmgmt_users']);
				break;
		}
	}
}