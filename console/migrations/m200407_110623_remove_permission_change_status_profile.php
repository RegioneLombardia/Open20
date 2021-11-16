<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m191003_121523_event_seats_permissions*/
class m200407_110623_remove_permission_change_status_profile extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [

            [
                'name' => 'CHANGE_USERPROFILE_WORKFLOW_STATUS',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN', 'AMMINISTRATORE_UTENTI', 'BASIC_USER', 'EVENT_DG', 'SUPER_USER_EVENT','SUPER_USER' ],
                ]
            ],

        ];

    }
}
