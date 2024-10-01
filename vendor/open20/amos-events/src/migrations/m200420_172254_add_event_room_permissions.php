<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\events\rules\EventRoomsRule;
use open20\amos\events\widgets\icons\WidgetIconEventRooms;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;

/**
 * Class m200420_172254_add_event_room_permissions
 */
class m200420_172254_add_event_room_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return ArrayHelper::merge(
            $this->setPluginRoles(),
            $this->setModelPermissions(),
            $this->setWidgetsPermissions()
        );
    }

    private function setPluginRoles()
    {
        return [
            [
                'name' => 'EVENTS_ROOMS_MANAGER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Role to manage EventRoom',
                'ruleName' => EventRoomsRule::className(),
                'parent' => ['EVENTS_ADMINISTRATOR']
            ]
        ];
    }

    private function setModelPermissions()
    {
        return [
            [
                'name' => 'EVENTROOM_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Create permission for model EventRoom',
                'parent' => ['EVENTS_ROOMS_MANAGER']
            ],
            [
                'name' => 'EVENTROOM_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Read permission for model EventRoom',
                'parent' => ['EVENTS_ROOMS_MANAGER']
            ],
            [
                'name' => 'EVENTROOM_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Update permission for model EventRoom',
                'parent' => ['EVENTS_ROOMS_MANAGER']
            ],
            [
                'name' => 'EVENTROOM_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Delete permission for model EventRoom',
                'parent' => ['EVENTS_ROOMS_MANAGER']
            ]
        ];
    }

    private function setWidgetsPermissions()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';
        return [
            [
                'name' => WidgetIconEventRooms::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconEventRooms',
                'parent' => ['EVENTS_ROOMS_MANAGER']
            ]
        ];
    }
}
