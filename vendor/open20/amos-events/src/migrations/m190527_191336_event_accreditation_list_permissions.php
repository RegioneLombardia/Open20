<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m190527_191336_event_accreditation_list_permissions*/
class m190527_191336_event_accreditation_list_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' =>  'EVENTACCREDITATIONLIST_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model EventAccreditationList',
                'ruleName' => null,
                'parent' => ['ADMIN', 'EVENTS_ADMINISTRATOR', 'EVENTS_MANAGER']
            ],
            [
                'name' =>  'EVENTACCREDITATIONLIST_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model EventAccreditationList',
                'ruleName' => null,
                'parent' => ['ADMIN', 'EVENTS_ADMINISTRATOR', 'EVENTS_MANAGER']
            ],
            [
                'name' =>  'EVENTACCREDITATIONLIST_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model EventAccreditationList',
                'ruleName' => null,
                'parent' => ['ADMIN', 'EVENTS_ADMINISTRATOR', 'EVENTS_MANAGER']
            ],
            [
                'name' =>  'EVENTACCREDITATIONLIST_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model EventAccreditationList',
                'ruleName' => null,
                'parent' => ['ADMIN', 'EVENTS_ADMINISTRATOR', 'EVENTS_MANAGER']
            ],

        ];
    }
}
