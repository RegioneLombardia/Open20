<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m201117_174914_agid_dataset_permissions*/
class m201117_174914_agid_dataset_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'AGIDDATASET_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model AgidDataset',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],
                [
                    'name' =>  'AGIDDATASET_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model AgidDataset',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                    ],
                [
                    'name' =>  'AGIDDATASET_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model AgidDataset',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],
                [
                    'name' =>  'AGIDDATASET_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model AgidDataset',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],

            ];
    }
}
