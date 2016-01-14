<?php
defined('TYPO3_MODE') || die();

$configuration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['mr_usrgrpmgmt']);
if (!isset($configuration['be_groups']) || (bool)$configuration['be_groups']) {

    // Create a virtual column to hold user assignment
    $tempColumns = array(
        'tx_mrusrgrpmgmt_users' => array(
            'displayCond' => 'REC:NEW:false',
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_tca.xlf:groups.tx_mrusrgrpmgmt_users',
            'config' => array(
                'type' => 'select',
                'itemsProcFunc' => 'Causal\\MrUsrgrpmgmt\\Tca\\ItemFunctions->users',
                'size' => '12',
                'minitems' => '0',
                'maxitems' => '999',
                'allowed' => 'be_users',
                'wizards' => array(
                    'suggest' => array(
                        'type' => 'suggest',
                    ),
                ),
            ),
        ),
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('be_groups', $tempColumns);
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('be_groups', 'tx_mrusrgrpmgmt_users;;;;1-1-1', '', 'after:subgroup');

}
