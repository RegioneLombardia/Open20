<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m200826_100000_operator_permissions*/
class m200826_100000_operator_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'AMMINISTRATORE_UTENTI',
                'update' => true,
                'newValues' => [
                    'addParents' => ['PC_STRUCTURE_ADMIN', 'PC_ADMIN'],
                ]
            ],
            [
                'name' => 'OPERATOR_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model OPERATOR',
                'ruleName' => null,
                'parent' => ['PC_STRUCTURE_ADMIN', 'PC_ADMIN']
            ],
            [
                'name' => 'OPERATOR_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model OPERATOR',
                'ruleName' => null,
                'parent' => ['PC_STRUCTURE_ADMIN', 'PC_ADMIN']
            ],
            [
                'name' => 'OPERATOR_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model OPERATOR',
                'ruleName' => null,
                'parent' => ['PC_STRUCTURE_ADMIN', 'PC_ADMIN']
            ],
            [
                'name' => 'OPERATOR_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model OPERATOR',
                'ruleName' => null,
                'parent' => ['PC_STRUCTURE_ADMIN', 'PC_ADMIN']
            ],

        ];
    }
}
