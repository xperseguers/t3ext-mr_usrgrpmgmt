<?php
defined('TYPO3_MODE') || die();

$boot = function ($_EXTKEY) {
    $config = unserialize($TYPO3_CONF_VARS['EXT']['extConf'][$_EXTKEY]);
    $manageUsers = false;

    $tables = array('be_groups', 'fe_groups');
    foreach ($tables as $table) {
        if (isset($config[$table]) && !$config[$table]) {
            continue;
        }
        $manageUsers = true;
    }

    if ($manageUsers) {
        // Register hooks into \TYPO3\CMS\Backend\Form\FormEngine and \TYPO3\CMS\Core\DataHandling\DataHandler
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getSingleFieldClass'][] = 'EXT:' . $_EXTKEY . '/Classes/Hooks/DataHandler.php:Causal\\MrUsrgrpmgmt\\Hooks\\FormEngine';
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:' . $_EXTKEY . '/Classes/Hooks/DataHandler.php:Causal\\MrUsrgrpmgmt\\Hooks\\DataHandler';
    }

};

$boot($_EXTKEY);
unset($boot);
