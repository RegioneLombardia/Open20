<?php

use yii\rbac\Permission;

class m230718_160000_update_prodotti_servizi_sub_cruds_read_permissions extends \open20\amos\core\migration\AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';
        return [
            [
                'name' =>  'PRODOTTIDESTINATARI_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model ProdottiDestinatari',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'removeParents' => ['PRODOTTIDESTINATARI_ADMIN']
                ]
            ],
            [
                'name' => 'PRODOTTISOLUZIONI_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model ProdottiSoluzioni',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'removeParents' => ['PRODOTTISOLUZIONI_ADMIN']
                ]
            ],
            [
                'name' =>  'PRODOTTIFINALITA_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model ProdottiFinalita',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'removeParents' => ['PRODOTTIFINALITA_ADMIN']
                ]
            ]
        ];
    }
}