<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m201030_090529_agid_service_permissions*/
class m201030_090529_agid_service_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'AGIDSERVICE_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model AgidService',
                    'ruleName' => null,
                    'parent' => ['ADMIN','ADMIN_FE']
                ],
                [
                    'name' =>  'AGIDSERVICE_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model AgidService',
                    'ruleName' => null,
                    'parent' => ['ADMIN','ADMIN_FE']
                    ],
                [
                    'name' =>  'AGIDSERVICE_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model AgidService',
                    'ruleName' => null,
                    'parent' => ['ADMIN','ADMIN_FE']
                ],
                [
                    'name' =>  'AGIDSERVICE_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model AgidService',
                    'ruleName' => null,
                    'parent' => ['ADMIN','ADMIN_FE']
                ],

            ];
    }
}
