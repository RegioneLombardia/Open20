<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m230417_182800_prodotti_destinatari_permissions*/
class m230417_182800_prodotti_destinatari_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' =>  'PRODOTTIDESTINATARI_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model ProdottiDestinatari',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' =>  'PRODOTTIDESTINATARI_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model ProdottiDestinatari',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' =>  'PRODOTTIDESTINATARI_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model ProdottiDestinatari',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' =>  'PRODOTTIDESTINATARI_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model ProdottiDestinatari',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
        ];
    }
}