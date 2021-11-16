<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m200402_182259_event_communication_permissions*/
class m200407_101059_event_fix_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'EVENTLOCATION_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['EVENTS_CONFIGURATIONS'],
                    'removeParents' => ['EVENTS_MANAGE_SECONDARY_TABLES']
                ]
            ],
            [
                'name' => 'EVENTLOCATION_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['EVENTS_CONFIGURATIONS'],
                    'removeParents' => ['EVENTS_MANAGE_SECONDARY_TABLES']
                ]
            ],
            [
                'name' => 'EVENTLOCATION_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['EVENTS_CONFIGURATIONS'],
                    'removeParents' => ['EVENTS_MANAGE_SECONDARY_TABLES']
                ]
            ],
            [
                'name' => 'EVENTLOCATION_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['EVENTS_CONFIGURATIONS'],
                    'removeParents' => ['EVENTS_MANAGE_SECONDARY_TABLES']
                ]
            ],

        ];
    }
}
