<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m201030_085944_agid_service_type_permissions*/
class m201030_085944_agid_service_type_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'AGIDSERVICETYPE_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model AgidServiceType',
                    'ruleName' => null,
                    'parent' => ['ADMIN','ADMIN_FE']
                ],
                [
                    'name' =>  'AGIDSERVICETYPE_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model AgidServiceType',
                    'ruleName' => null,
                    'parent' => ['ADMIN','ADMIN_FE']
                    ],
                [
                    'name' =>  'AGIDSERVICETYPE_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model AgidServiceType',
                    'ruleName' => null,
                    'parent' => ['ADMIN','ADMIN_FE']
                ],
                [
                    'name' =>  'AGIDSERVICETYPE_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model AgidServiceType',
                    'ruleName' => null,
                    'parent' => ['ADMIN','ADMIN_FE']
                ],

            ];
    }
}
