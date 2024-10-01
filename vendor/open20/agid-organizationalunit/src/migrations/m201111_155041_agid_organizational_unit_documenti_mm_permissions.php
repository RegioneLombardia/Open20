<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m201111_155041_agid_organizational_unit_documenti_mm_permissions*/
class m201111_155041_agid_organizational_unit_documenti_mm_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'AGIDORGANIZATIONALUNITDOCUMENTIMM_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model AgidOrganizationalUnitDocumentiMm',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],
                [
                    'name' =>  'AGIDORGANIZATIONALUNITDOCUMENTIMM_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model AgidOrganizationalUnitDocumentiMm',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                    ],
                [
                    'name' =>  'AGIDORGANIZATIONALUNITDOCUMENTIMM_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model AgidOrganizationalUnitDocumentiMm',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],
                [
                    'name' =>  'AGIDORGANIZATIONALUNITDOCUMENTIMM_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model AgidOrganizationalUnitDocumentiMm',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],

            ];
    }
}
