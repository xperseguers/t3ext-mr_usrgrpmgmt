<?php
defined('TYPO3_MODE') || die();

$boot = function ($_EXTKEY) {
    $config = unserialize($TYPO3_CONF_VARS['EXT']['extConf'][$_EXTKEY]);
    $manageUsers = false;

    $tables = array('be_groups', 'fe_groups');
    foreach ($tables as $table) {
        if (!isset($config[$table]) || (bool)$config[$table]) {
            $manageUsers = true;
        }
    }

    if ($manageUsers) {
        if (version_compare(TYPO3_version, '7.3', '>=')) {
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord']['Causal\\MrUsrgrpmgmt\\Form\\FormDataProvider\\UsersFromGroup'] = array(
                'depends' => array(
                    'TYPO3\\CMS\\Backend\\Form\\FormDataProvider\\DatabaseEditRow',
                ),
            );
        } else {
            $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getSingleFieldClass'][] = 'EXT:' . $_EXTKEY . '/Classes/Hooks/DataHandler.php:Causal\\MrUsrgrpmgmt\\Hooks\\FormEngine';
        }

        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:' . $_EXTKEY . '/Classes/Hooks/DataHandler.php:Causal\\MrUsrgrpmgmt\\Hooks\\DataHandler';
    }

};

$boot($_EXTKEY);
unset($boot);
