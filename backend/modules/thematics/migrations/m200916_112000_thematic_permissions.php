<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m200916_112000_thematic_permissions*/
class m200916_112000_thematic_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            
            [
                'name' => 'THEMATIC_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model THEMATIC',
                'ruleName' => null,
                'parent' => ['PC_ADMIN','PC_EDITOR'],
            ],
            [
                'name' => 'THEMATIC_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model THEMATIC',
                'ruleName' => null,
                'parent' => ['PC_ADMIN','PC_EDITOR'],
            ],
            [
                'name' => 'THEMATIC_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model THEMATIC',
                'ruleName' => null,
                'parent' => ['PC_ADMIN','PC_EDITOR'],
            ],
            [
                'name' => 'THEMATIC_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model THEMATIC',
                'ruleName' => null,
                'parent' => ['PC_ADMIN','PC_EDITOR'],
            ],

        ];
    }
}
