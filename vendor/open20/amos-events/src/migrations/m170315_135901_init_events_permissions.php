<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\events\rules\DeleteOwnEventsRule;
use open20\amos\events\rules\UpdateOwnEventsRule;
use yii\rbac\Permission;

/**
 * Class m170315_135901_init_events_permissions
 */
class m170315_135901_init_events_permissions extends AmosMigrationPermissions
{
    /**
     * Use this function to map permissions, roles and associations between permissions and roles. If you don't need to
     * to add or remove any permissions or roles you have to delete this method.
     */
    protected function setAuthorizations()
    {
        $this->authorizations = array_merge(
            $this->setPluginRoles(),
            $this->setModelPermissions(),
            $this->setWidgetsPermissions()
        );
    }

    private function setPluginRoles()
    {
        return [
            [
                'name' => 'EVENTS_ADMINISTRATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Administrator role for events plugin',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'EVENTS_CREATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Events creator role for events plugin',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR']
            ],
            [
                'name' => 'EVENTS_VALIDATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Events validator role for events plugin',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR']
            ],
            [
                'name' => 'PLATFORM_EVENTS_VALIDATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Platform events validator role for events plugin',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR']
            ],
            [
                'name' => 'EVENTS_READER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Platform events reader role for events plugin',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR']
            ],
            [
                'name' => 'EVENTS_MANAGER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Platform events manager role for events plugin',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR']
            ]
        ];
    }

    private function setModelPermissions()
    {
        return [

            // Permissions for model Event
            [
                'name' => 'EVENT_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Create permission for model Event',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR', 'EVENTS_CREATOR']
            ],
            [
                'name' => 'EVENT_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Read permission for model Event',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR', 'EVENTS_CREATOR', 'EVENTS_VALIDATOR', 'PLATFORM_EVENTS_VALIDATOR', 'EVENTS_READER', 'EVENTS_MANAGER']
            ],
            [
                'name' => 'EVENT_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Update permission for model Event',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR', UpdateOwnEventsRule::className(), 'EVENTS_VALIDATOR', 'PLATFORM_EVENTS_VALIDATOR', 'EVENTS_MANAGER']
            ],
            [
                'name' => 'EVENT_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Delete permission for model Event',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR', DeleteOwnEventsRule::className()]
            ],
            [
                'name' => UpdateOwnEventsRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to modify own event',
                'ruleName' => UpdateOwnEventsRule::className(),
                'parent' => ['EVENTS_CREATOR']
            ],
            [
                'name' => DeleteOwnEventsRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to delete own event',
                'ruleName' => DeleteOwnEventsRule::className(),
                'parent' => ['EVENTS_CREATOR']
            ],

            // Permissions for model EventType: ONLY FOR EVENTS_ADMINISTRATOR!!!
            [
                'name' => 'EVENTTYPE_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Create permission for model EventType',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR']
            ],
            [
                'name' => 'EVENTTYPE_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Read permission for model EventType',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR']
            ],
            [
                'name' => 'EVENTTYPE_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Update permission for model EventType',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR']
            ],
            [
                'name' => 'EVENTTYPE_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Delete permission for model EventType',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR']
            ]
        ];
    }

    private function setWidgetsPermissions()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';
        return [
            [
                'name' => \open20\amos\events\widgets\icons\WidgetIconEvents::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconEvents',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR', 'EVENTS_READER']
            ],
            [
                'name' => \open20\amos\events\widgets\icons\WidgetIconEventTypes::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconEventTypes',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR']
            ],
            [
                'name' => \open20\amos\events\widgets\icons\WidgetIconEventsCreatedBy::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconEventsCreatedBy',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR', 'EVENTS_CREATOR']
            ],
            [
                'name' => \open20\amos\events\widgets\icons\WidgetIconEventsToPublish::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconEventsToPublish',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR', 'EVENTS_VALIDATOR', 'PLATFORM_EVENTS_VALIDATOR']
            ],
            [
                'name' => \open20\amos\events\widgets\icons\WidgetIconEventsManagement::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconEventsManagement',
                'ruleName' => null,
                'parent' => ['EVENTS_MANAGER']
            ]
        ];
    }
}
