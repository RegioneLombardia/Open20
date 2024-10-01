<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m201102_154824_agid_person_permissions*/
class m201102_154824_agid_person_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'AGIDPERSON_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model AgidPerson',
                    'ruleName' => null,
                    'parent' => ['ADMIN','ADMIN_FE']
                ],
                [
                    'name' =>  'AGIDPERSON_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model AgidPerson',
                    'ruleName' => null,
                    'parent' => ['ADMIN','ADMIN_FE']
                    ],
                [
                    'name' =>  'AGIDPERSON_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model AgidPerson',
                    'ruleName' => null,
                    'parent' => ['ADMIN','ADMIN_FE']
                ],
                [
                    'name' =>  'AGIDPERSON_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model AgidPerson',
                    'ruleName' => null,
                    'parent' => ['ADMIN','ADMIN_FE']
                ],

            ];
    }
}
