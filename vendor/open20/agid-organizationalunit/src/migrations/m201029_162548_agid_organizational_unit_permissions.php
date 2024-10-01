<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m201029_162548_agid_organizational_unit_permissions*/
class m201029_162548_agid_organizational_unit_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'AGIDORGANIZATIONALUNIT_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model AgidOrganizationalUnit',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],
                [
                    'name' =>  'AGIDORGANIZATIONALUNIT_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model AgidOrganizationalUnit',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                    ],
                [
                    'name' =>  'AGIDORGANIZATIONALUNIT_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model AgidOrganizationalUnit',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],
                [
                    'name' =>  'AGIDORGANIZATIONALUNIT_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model AgidOrganizationalUnit',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],

            ];
    }
}
