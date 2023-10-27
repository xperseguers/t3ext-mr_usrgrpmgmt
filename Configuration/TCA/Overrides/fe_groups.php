<?php
defined('TYPO3') || die();

$config = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class
)->get('mr_usrgrpmgmt') ?? [];

if ((bool)($config['fe_groups'] ?? true)) {
    // Create a virtual column to hold user assignment
    $tempColumns = [
        'tx_mrusrgrpmgmt_users' => [
            'displayCond' => 'REC:NEW:false',
            'exclude' => 1,
            'label' => 'LLL:EXT:mr_usrgrpmgmt/Resources/Private/Language/locallang_tca.xlf:groups.tx_mrusrgrpmgmt_users',
            'config' => [
                'type' => 'select',
                'itemsProcFunc' => \Causal\MrUsrgrpmgmt\Tca\ItemFunctions::class . '->users',
                'size' => '12',
                'minitems' => '0',
                'maxitems' => '999',
                'allowed' => 'fe_users',
                'wizards' => [
                    'suggest' => [
                        'type' => 'suggest',
                    ],
                ],
            ],
        ],
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        'fe_groups',
        $tempColumns
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'fe_groups',
        'tx_mrusrgrpmgmt_users',
        '',
        'after:subgroup'
    );
}
