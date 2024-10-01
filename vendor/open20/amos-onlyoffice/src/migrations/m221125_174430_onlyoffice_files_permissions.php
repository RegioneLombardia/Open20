<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m221125_174430_onlyoffice_files_permissions */
class m221125_174430_onlyoffice_files_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'ONLYOFFICEFILES_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model OnlyofficeFiles',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'ONLYOFFICEFILES_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model OnlyofficeFiles',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'ONLYOFFICEFILES_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model OnlyofficeFiles',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'ONLYOFFICEFILES_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model OnlyofficeFiles',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
        ];
    }
}