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
class m200402_102223_permission_role_to_event_dg_event extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [

            [
                'name' => 'AMMINISTRATORE_UTENTI',
                'update' => true,
                'newValues' => [
                    'addParents' => ['EVENT_DG'],
                ]
            ],
            [
                'name' => 'PRIVILEGES_MANAGER',
                'update' => true,
                'newValues' => [
                    'addParents' => ['EVENT_DG'],
                ]
            ],
            [
                'name' => 'CHANGE_USERPROFILE_WORKFLOW_STATUS',
                'update' => true,
                'newValues' => [
                    'addParents' => ['EVENT_DG'],
                ]
            ],
            [
                'name' => 'AMMINISTRATORE_USERIMPORTTASK',
                'update' => true,
                'newValues' => [
                    'addParents' => ['EVENT_DG'],
                ]
            ],
            [
                'name' => 'AMMINISTRATORE_USERIMPORTTASK',
                'update' => true,
                'newValues' => [
                    'addParents' => ['EVENT_DG_OPERATOR'],
                ]
            ]
        ];

    }
}
