<?php
defined('TYPO3_MODE') or die();

$config = unserialize($TYPO3_CONF_VARS['EXT']['extConf'][$_EXTKEY]);
$manageUsers = FALSE;

// Create a virtual column to hold user assignment
$newColumns = array(
	'tx_mrusrgrpmgmt_users' => array(
		'displayCond' => 'REC:NEW:false',
		'exclude' => 1,
		'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_tca.xlf:groups.tx_mrusrgrpmgmt_users',
		'config' => array(
			'type' => 'select',
			'itemsProcFunc' => 'tx_mrusrgrpmgmt_itemfunctions->users',
			'size' => '12',
			'minitems' => '0',
			'maxitems' => '999',
			'allowed' => '',	// defined below
			'wizards' => array(
				'suggest' => array(
					'type' => 'suggest',
				),
			),
		),
	),
);

$tables = array('be_groups', 'fe_groups');
foreach ($tables as $table) {
	if (isset($config[$table]) && !$config[$table]) {
		continue;
	}
	if (version_compare(TYPO3_version, '6.0.0', '<')) {
		t3lib_div::loadTCA($table);
	}
	$newColumns['tx_mrusrgrpmgmt_users']['config']['allowed'] = ($table == 'be_groups' ? 'be_users' : 'fe_users');
	t3lib_extMgm::addTCAcolumns($table, $newColumns);
	t3lib_extMgm::addToAllTCAtypes($table, 'tx_mrusrgrpmgmt_users;;;;1-1-1', '','after:subgroup');

	$manageUsers = TRUE;
}

if ($manageUsers) {
	// Register hooks into t3lib_tceforms and t3lib_tcemain
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getSingleFieldClass'][] = 'EXT:' . $_EXTKEY . '/hooks/class.tx_mrusrgrpmgmt_tce.php:tx_mrusrgrpmgmt_tce';
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:' . $_EXTKEY . '/hooks/class.tx_mrusrgrpmgmt_tce.php:tx_mrusrgrpmgmt_tce';

	include_once(t3lib_extMgm::extPath($_EXTKEY) . 'classes/class.tx_mrusrgrpmgmt_itemfunctions.php');
}
