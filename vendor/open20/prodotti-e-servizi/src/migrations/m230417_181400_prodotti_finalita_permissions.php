<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m230417_181400_prodotti_finalita_permissions*/
class m230417_181400_prodotti_finalita_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' =>  'PRODOTTIFINALITA_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model ProdottiFinalita',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' =>  'PRODOTTIFINALITA_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model ProdottiFinalita',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' =>  'PRODOTTIFINALITA_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model ProdottiFinalita',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' =>  'PRODOTTIFINALITA_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model ProdottiFinalita',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
        ];
    }
}
