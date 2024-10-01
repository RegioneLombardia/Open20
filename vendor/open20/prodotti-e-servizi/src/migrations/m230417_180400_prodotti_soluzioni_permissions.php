<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m230417_180400_prodotti_soluzioni_permissions*/
class m230417_180400_prodotti_soluzioni_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'PRODOTTISOLUZIONI_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model ProdottiSoluzioni',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'PRODOTTISOLUZIONI_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model ProdottiSoluzioni',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'PRODOTTISOLUZIONI_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model ProdottiSoluzioni',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'PRODOTTISOLUZIONI_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model ProdottiSoluzioni',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
        ];
    }
}