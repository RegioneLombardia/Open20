<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m201104_124640_agid_person_organizational_unit_mm_permissions*/
class m201106_174640_agid_person_documenti_mm_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'AGIDPERSONORGANIZATIONALUNITMM_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model AgidPersonOrganizationalUnitMm',
                    'ruleName' => null,
                    'parent' => ['ADMIN','ADMIN_FE']
                ],
                [
                    'name' =>  'AGIDPERSONORGANIZATIONALUNITMM_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model AgidPersonOrganizationalUnitMm',
                    'ruleName' => null,
                    'parent' => ['ADMIN','ADMIN_FE']
                    ],
                [
                    'name' =>  'AGIDPERSONORGANIZATIONALUNITMM_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model AgidPersonOrganizationalUnitMm',
                    'ruleName' => null,
                    'parent' => ['ADMIN','ADMIN_FE']
                ],
                [
                    'name' =>  'AGIDPERSONORGANIZATIONALUNITMM_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model AgidPersonOrganizationalUnitMm',
                    'ruleName' => null,
                    'parent' => ['ADMIN','ADMIN_FE']
                ],

            ];
    }
}
