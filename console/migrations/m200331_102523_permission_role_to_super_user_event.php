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
class m200331_102523_permission_role_to_super_user_event extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [

            [
                'name' => 'SUPER_USER_EVENT',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SUPER_USER'],
                ]
            ],
            [
                'name' => \open20\amos\events\widgets\icons\WidgetIconManageAllEvents::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['SUPER_USER_EVENT'],
                ]
            ],
            [
                'name' => \open20\amos\events\widgets\icons\WidgetIconManageEvents::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['SUPER_USER_EVENT'],
                ]
            ],
            [
                'name' => \open20\amos\events\widgets\icons\WidgetIconCreateNewEvent::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['SUPER_USER_EVENT'],
                ]
            ],

            [
                'name' => \open20\amos\events\widgets\icons\WidgetIconEventDg::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['SUPER_USER_EVENT'],
                ]
            ],

            [
                'name' => \open20\amos\events\widgets\icons\WidgetIconEventOperators::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['SUPER_USER_EVENT'],
                ]
            ],
            [
                'name' => 'EVENTS_MANAGE_GROUP',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SUPER_USER_EVENT'],
                ]
            ],
            [
                'name' => 'EVENTS_CREATOR',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SUPER_USER_EVENT'],
                ]
            ],
            [
                'name' => 'AMMINISTRATORE_UTENTI',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SUPER_USER_EVENT'],
                ]
            ],
            [
                'name' => 'PRIVILEGES_MANAGER',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SUPER_USER_EVENT'],
                ]
            ],
            [
                'name' => 'CHANGE_USERPROFILE_WORKFLOW_STATUS',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SUPER_USER_EVENT'],
                ]
            ],
        ];

    }
}
