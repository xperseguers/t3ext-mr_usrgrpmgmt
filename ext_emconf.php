<?php

########################################################################
# Extension Manager/Repository config file for ext "mr_usrgrpmgmt".
#
# Auto generated 02-06-2010 16:13
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'User Group Management',
	'description' => 'This is a backend-related extension to manage both backend and frontend users from the edit form of backend/frontend groups. Easily assign users to groups or remove them from groups.',
	'category' => 'be',
	'author' => 'Xavier Perseguers (Hemmer.ch SA)',
	'author_email' => 'typo3@perseguers.ch',
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
	'author_company' => 'Hemmer.ch SA',
	'version' => '1.1.1',
	'constraints' => array(
		'depends' => array(
			'typo3' => '4.3.0-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:9:{s:9:"ChangeLog";s:4:"efcb";s:21:"ext_conf_template.txt";s:4:"07c8";s:12:"ext_icon.gif";s:4:"37d5";s:14:"ext_tables.php";s:4:"2829";s:13:"locallang.xml";s:4:"234c";s:17:"locallang_tca.xml";s:4:"8809";s:47:"classes/class.tx_mrusrgrpmgmt_itemfunctions.php";s:4:"59b7";s:14:"doc/manual.sxw";s:4:"cd7e";s:35:"hooks/class.tx_mrusrgrpmgmt_tce.php";s:4:"1c62";}',
	'suggests' => array(
	),
);

?>