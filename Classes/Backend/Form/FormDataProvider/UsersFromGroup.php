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

namespace Causal\MrUsrgrpmgmt\Backend\Form\FormDataProvider;

use Causal\MrUsrgrpmgmt\Traits\AssignedUsersTrait;
use TYPO3\CMS\Backend\Form\FormDataProviderInterface;

/**
 * Additional data when editing a record.
 *
 * @category    Form\FormDataProvider
 * @package     TYPO3
 * @subpackage  tx_mrusrgrpmgmt
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   2016-2023 Causal Sàrl
 * @license     https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class UsersFromGroup implements FormDataProviderInterface
{
    use AssignedUsersTrait;

    /**
     * Fetches users from a given group record.
     *
     * @param array $result
     * @return array
     */
    public function addData(array $result)
    {
        if (in_array($result['tableName'], ['be_groups', 'fe_groups'], true)) {
            $userUids = $this->getAssignedUsers($result['tableName'], $result['databaseRow']['uid']);
            $result['databaseRow']['tx_mrusrgrpmgmt_users'] = [];
            foreach ($userUids as $userUid) {
                $result['databaseRow']['tx_mrusrgrpmgmt_users'][] = $userUid;
            }
        }

        return $result;
    }
}
