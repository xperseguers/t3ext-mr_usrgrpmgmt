<?php
defined('TYPO3') || die();

(static function (string $_EXTKEY) {
    $config = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
        \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class
    )->get($_EXTKEY) ?? [];
    $manageUsers = false;

    $tables = ['be_groups', 'fe_groups'];
    foreach ($tables as $table) {
        if ((bool)($config[$table] ?? false)) {
            $manageUsers = true;
            break;
        }
    }

    if ($manageUsers) {
        // Extend the record with virtual fields when editing
        $dataProviders =& $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'];
        $dataProviders[\Causal\MrUsrgrpmgmt\Backend\Form\FormDataProvider\UsersFromGroup::class] = [
            'after' => [
                \TYPO3\CMS\Backend\Form\FormDataProvider\DatabaseEditRow::class,
            ]
        ];

        // Register hooks for \TYPO3\CMS\Core\DataHandling\DataHandler
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][]
            = \Causal\MrUsrgrpmgmt\Hooks\DataHandler::class;
    }

})('mr_usrgrpmgmt');
