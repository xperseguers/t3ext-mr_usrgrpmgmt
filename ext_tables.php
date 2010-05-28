<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$config = unserialize($TYPO3_CONF_VARS['EXT']['extConf'][$_EXTKEY]);
$manageUsers = FALSE;

if ($config['be_groups']) {
		// Create a virtual column to hold user assignment
	$newColumns = array(
		'tx_mrusrgrpmgmt_users' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mr_usrgrpmgmt/locallang_tca.xml:groups.tx_mrusrgrpmgmt_users',
			'config' => array(
				'type' => 'select',
				'itemsProcFunc' => 'tx_mrusrgrpmgmt_itemfunctions->users',
				'size' => '12',
				'minitems' => '0',
				'maxitems' => '999'
			),
		),
	);
	
	t3lib_div::loadTCA('be_groups');
	t3lib_extMgm::addTCAcolumns('be_groups', $newColumns, 1);
	t3lib_extMgm::addToAllTCAtypes('be_groups', 'tx_mrusrgrpmgmt_users;;;;1-1-1', '','after:subgroup');

	$manageUsers = TRUE;
}

if ($config['fe_groups']) {
		// Create a virtual column to hold user assignment
	$newColumns = array(
		'tx_mrusrgrpmgmt_users' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:mr_usrgrpmgmt/locallang_tca.xml:groups.tx_mrusrgrpmgmt_users',
			'config' => array(
				'type' => 'select',
				'itemsProcFunc' => 'tx_mrusrgrpmgmt_itemfunctions->users',
				'size' => '12',
				'minitems' => '0',
				'maxitems' => '999'
			),
		),
	);
	
	t3lib_div::loadTCA('fe_groups');
	t3lib_extMgm::addTCAcolumns('fe_groups', $newColumns, 1);
	t3lib_extMgm::addToAllTCAtypes('fe_groups', 'tx_mrusrgrpmgmt_users;;;;1-1-1', '','after:subgroup');

	$manageUsers = TRUE;
}

if ($manageUsers) {
		// Register hook into t3lib_tceforms and t3lib_tcemain
	$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getSingleFieldClass'][] = 'EXT:mr_usrgrpmgmt/hooks/class.tx_mrusrgrpmgmt_tce.php:tx_mrusrgrpmgmt_tce';
	$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:mr_usrgrpmgmt/hooks/class.tx_mrusrgrpmgmt_tce.php:tx_mrusrgrpmgmt_tce';

	include_once(t3lib_extMgm::extPath($_EXTKEY) . 'classes/class.tx_mrusrgrpmgmt_itemfunctions.php');
}
?>