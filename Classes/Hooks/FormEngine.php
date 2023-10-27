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

namespace Causal\MrUsrgrpmgmt\Hooks;

use Causal\MrUsrgrpmgmt\Traits\AssignedUsersTrait;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;

/**
 * Hook to pre-process a single field in \TYPO3\CMS\Backend\Form\FormEngine.
 *
 * @category    Hooks
 * @package     TYPO3
 * @subpackage  tx_mrusrgrpmgmt
 * @author      Xavier Perseguers <xavier@causal.ch>
 * @copyright   2010-2023 Causal Sàrl
 * @license     https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
class FormEngine
{
    use AssignedUsersTrait;

    /**
     * Pre-processes the tceform rendering to specify currently assigned users.
     *
     * @param string $table
     * @param string $field
     * @param array $row
     * @param array $PA
     */
    public function getSingleField_beforeRender(string $table, string $field, array $row, array &$PA)
    {
        if (GeneralUtility::inList('be_groups,fe_groups', $table) && $field === 'tx_mrusrgrpmgmt_users') {
            $users = $this->getAssignedUsers($table, $row['uid']);
            $list = [];
            foreach ($users as $user) {
                $list[] = $user['uid'];
            }
            $PA['itemFormElValue'] = implode(',', $list);
        }
    }
}
