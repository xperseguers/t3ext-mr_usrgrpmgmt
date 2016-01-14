<?php

########################################################################
# Extension Manager/Repository config file for ext "mr_usrgrpmgmt".
#
# Auto generated 23-08-2010 09:52
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'User Group Management',
	'description' => 'This is a Backend-related extension to manage both Backend and Frontend users from the edit form of Backend/Frontend groups. Easily assign users to groups or remove them from groups.',
	'category' => 'be',
	'author' => 'Xavier Perseguers',
	'author_email' => 'xavier@causal.ch',
	'author_company' => 'Causal Sàrl',
	'shy' => '',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'doNotLoadInFE' => 1,
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'version' => '1.2.0-dev',
	'constraints' => array(
		'depends' => array(
			'typo3' => '4.5.0-6.2.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:9:{s:9:"ChangeLog";s:4:"cb0d";s:21:"ext_conf_template.txt";s:4:"07c8";s:12:"ext_icon.gif";s:4:"37d5";s:14:"ext_tables.php";s:4:"2829";s:13:"locallang.xml";s:4:"234c";s:17:"locallang_tca.xml";s:4:"8809";s:47:"classes/class.tx_mrusrgrpmgmt_itemfunctions.php";s:4:"5f90";s:14:"doc/manual.sxw";s:4:"cd7e";s:35:"hooks/class.tx_mrusrgrpmgmt_tce.php";s:4:"1c62";}',
	'suggests' => array(
	),
);

?>