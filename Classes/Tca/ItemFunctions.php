<?php
declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace Causal\MrUsrgrpmgmt\Tca;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;

/**
 * TCA helper for extension mr_usrgrpmgmt.
 *
 * @category    TCA
 * @package     TYPO3
 * @subpackage  tx_mrusrgrpmgmt
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   2010-2023 Causal Sàrl
 * @license     https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class ItemFunctions
{
    /**
     * Prepares the list of frontend users.
     *
     * @param array $params
     * @param object $pObj
     */
    public function getUsers(array &$params, $pObj)
    {
        if (!in_array($params['table'], ['be_groups', 'fe_groups'], true)) {
            return;
        }

        $userTable = ($params['table'] === 'be_groups' ? 'be_users' : 'fe_users');
        $statement = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable($userTable)
            ->select(
                ['*'],
                $userTable
            );
        while (($row = $statement->fetchAssociative()) !== false) {
            $label = BackendUtility::getRecordTitle($userTable, $row);
            $params['items'][] = array($label, $row['uid']);
        }
    }
}
