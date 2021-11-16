<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m200402_182259_event_communication_permissions*/
class m200409_101059_event_fix_permissions_sm extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'SITEMANAGEMENTSLIDERELEM_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['EVENT_DG_OPERATOR','SUPER_USER_EVENT'],
                ]
            ],
            [
                'name' => 'SITEMANAGEMENTSLIDERELEM_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['EVENT_DG_OPERATOR','SUPER_USER_EVENT'],
                ]
            ],
            [
                'name' => 'SITEMANAGEMENTSLIDERELEM_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['EVENT_DG_OPERATOR','SUPER_USER_EVENT'],
                ]
            ],
            [
                'name' => 'SITEMANAGEMENTSLIDERELEM_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['EVENT_DG_OPERATOR','SUPER_USER_EVENT'],
                ]
            ],

        ];
    }
}
