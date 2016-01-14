<?php
defined('TYPO3_MODE') || die();

$boot = function ($_EXTKEY) {
    $config = unserialize($TYPO3_CONF_VARS['EXT']['extConf'][$_EXTKEY]);
    $manageUsers = false;

    // Create a virtual column to hold user assignment
    $newColumns = array(
        'tx_mrusrgrpmgmt_users' => array(
            'displayCond' => 'REC:NEW:false',
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_tca.xlf:groups.tx_mrusrgrpmgmt_users',
            'config' => array(
                'type' => 'select',
                'itemsProcFunc' => 'Causal\\MrUsrgrpmgmt\\ItemFunctions->users',
                'size' => '12',
                'minitems' => '0',
                'maxitems' => '999',
                'allowed' => '',    // defined below
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
        $newColumns['tx_mrusrgrpmgmt_users']['config']['allowed'] = ($table === 'be_groups' ? 'be_users' : 'fe_users');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns($table, $newColumns);
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes($table, 'tx_mrusrgrpmgmt_users;;;;1-1-1', '', 'after:subgroup');

        $manageUsers = true;
    }

    if ($manageUsers) {
        // Register hooks into \TYPO3\CMS\Backend\Form\FormEngine and \TYPO3\CMS\Core\DataHandling\DataHandler
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getSingleFieldClass'][] = 'EXT:' . $_EXTKEY . '/Classes/Hooks/DataHandler.php:Causal\\MrUsrgrpmgmt\\FormEngine';
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:' . $_EXTKEY . '/Classes/Hooks/DataHandler.php:Causal\\MrUsrgrpmgmt\\DataHandler';
    }

};

$boot($_EXTKEY);
unset($boot);
