<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m201029_162509_agid_organizational_unit_content_type_permissions*/
class m201029_162509_agid_organizational_unit_content_type_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'AGIDORGANIZATIONALUNITCONTENTTYPE_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model AgidOrganizationalUnitContentType',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],
                [
                    'name' =>  'AGIDORGANIZATIONALUNITCONTENTTYPE_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model AgidOrganizationalUnitContentType',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                    ],
                [
                    'name' =>  'AGIDORGANIZATIONALUNITCONTENTTYPE_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model AgidOrganizationalUnitContentType',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],
                [
                    'name' =>  'AGIDORGANIZATIONALUNITCONTENTTYPE_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model AgidOrganizationalUnitContentType',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],

            ];
    }
}
