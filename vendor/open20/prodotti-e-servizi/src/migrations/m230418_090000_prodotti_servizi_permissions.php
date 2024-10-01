<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m230418_090000_prodotti_servizi_permissions*/
class m230418_090000_prodotti_servizi_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' =>  'PRODOTTISERVIZI_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model ProdottiServizi',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' =>  'PRODOTTISERVIZI_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model ProdottiServizi',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' =>  'PRODOTTISERVIZI_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model ProdottiServizi',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' =>  'PRODOTTISERVIZI_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model ProdottiServizi',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
        ];
    }
}