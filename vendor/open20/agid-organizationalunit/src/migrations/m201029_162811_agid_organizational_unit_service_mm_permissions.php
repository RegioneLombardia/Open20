<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m201029_162811_agid_organizational_unit_service_mm_permissions*/
class m201029_162811_agid_organizational_unit_service_mm_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'AGIDORGANIZATIONALUNITSERVICEMM_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model AgidOrganizationalUnitServiceMm',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],
                [
                    'name' =>  'AGIDORGANIZATIONALUNITSERVICEMM_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model AgidOrganizationalUnitServiceMm',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                    ],
                [
                    'name' =>  'AGIDORGANIZATIONALUNITSERVICEMM_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model AgidOrganizationalUnitServiceMm',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],
                [
                    'name' =>  'AGIDORGANIZATIONALUNITSERVICEMM_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model AgidOrganizationalUnitServiceMm',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],

            ];
    }
}
