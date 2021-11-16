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
class m200331_101523_permission_role_super_user_event extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [

            [
                'name' => 'SUPER_USER_EVENT',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Super user event',
                'parent' => ['EVENTS_ADMINISTRATOR']
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
                'name' => \open20\amos\events\models\Event::EVENTS_WORKFLOW_STATUS_PUBLISHED,
                'update' => true,
                'newValues' => [
                    'addParents' => ['SUPER_USER_EVENT'],
                ]
            ],
        ];

    }
}
