<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m200420_161524_event_container_permissions*/
class m200420_161524_event_container_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'EVENTCONTAINER_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model EventContainer',
                    'ruleName' => null,
                    'parent' => ['SUPER_USER_EVENT', 'EVENT_DG_OPERATOR','EVENTS_ADMINISTRATOR']
                ],
                [
                    'name' =>  'EVENTCONTAINER_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model EventContainer',
                    'ruleName' => null,
                    'parent' => ['SUPER_USER_EVENT','EVENT_DG_OPERATOR','EVENTS_ADMINISTRATOR']
                    ],
                [
                    'name' =>  'EVENTCONTAINER_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model EventContainer',
                    'ruleName' => null,
                    'parent' => ['SUPER_USER_EVENT', 'EVENT_DG_OPERATOR','EVENTS_ADMINISTRATOR']
                ],
                [
                    'name' =>  'EVENTCONTAINER_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model EventContainer',
                    'ruleName' => null,
                    'parent' => ['SUPER_USER_EVENT', 'EVENT_DG_OPERATOR','EVENTS_ADMINISTRATOR']
                ],

            ];
    }
}
