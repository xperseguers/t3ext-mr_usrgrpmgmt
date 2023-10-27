<?php
defined('TYPO3') || die();

$config = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class
)->get('mr_usrgrpmgmt') ?? [];

if ((bool)($config['be_groups'] ?? true)) {
    // Create a virtual column to hold user assignment
    $tempColumns = [
        'tx_mrusrgrpmgmt_users' => [
            'label' => 'LLL:EXT:mr_usrgrpmgmt/Resources/Private/Language/locallang_tca.xlf:groups.tx_mrusrgrpmgmt_users',
            'exclude' => 1,
            'displayCond' => 'REC:NEW:false',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'itemsProcFunc' => \Causal\MrUsrgrpmgmt\Tca\ItemFunctions::class . '->getUsers',
                'size' => '12',
                'minitems' => '0',
                'maxitems' => '999',
                'allowed' => 'be_users',
                'wizards' => [
                    'suggest' => [
                        'type' => 'suggest',
                    ],
                ],
            ],
        ],
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        'be_groups',
        $tempColumns
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'be_groups',
        'tx_mrusrgrpmgmt_users',
        '',
        'after:subgroup'
    );
}
