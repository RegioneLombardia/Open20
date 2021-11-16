<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m200305_171114_event_internal_list_permissions*/
class m200305_171114_event_internal_list_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'EVENTINTERNALLIST_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model EventInternalList',
                    'ruleName' => null,
                    'parent' => ['EVENTS_ADMINISTRATOR']
                ],
                [
                    'name' =>  'EVENTINTERNALLIST_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model EventInternalList',
                    'ruleName' => null,
                    'parent' => ['EVENTS_ADMINISTRATOR']
                    ],
                [
                    'name' =>  'EVENTINTERNALLIST_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model EventInternalList',
                    'ruleName' => null,
                    'parent' => ['EVENTS_ADMINISTRATOR']
                ],
                [
                    'name' =>  'EVENTINTERNALLIST_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model EventInternalList',
                    'ruleName' => null,
                    'parent' => ['EVENTS_ADMINISTRATOR']
                ],

            ];
    }
}
