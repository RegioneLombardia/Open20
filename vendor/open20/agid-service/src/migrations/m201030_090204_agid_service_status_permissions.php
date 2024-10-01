<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m201030_090204_agid_service_status_permissions*/
class m201030_090204_agid_service_status_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'AGIDSERVICESTATUS_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model AgidServiceStatus',
                    'ruleName' => null,
                    'parent' => ['ADMIN','ADMIN_FE']
                ],
                [
                    'name' =>  'AGIDSERVICESTATUS_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model AgidServiceStatus',
                    'ruleName' => null,
                    'parent' => ['ADMIN','ADMIN_FE']
                    ],
                [
                    'name' =>  'AGIDSERVICESTATUS_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model AgidServiceStatus',
                    'ruleName' => null,
                    'parent' => ['ADMIN','ADMIN_FE']
                ],
                [
                    'name' =>  'AGIDSERVICESTATUS_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model AgidServiceStatus',
                    'ruleName' => null,
                    'parent' => ['ADMIN','ADMIN_FE']
                ],

            ];
    }
}
