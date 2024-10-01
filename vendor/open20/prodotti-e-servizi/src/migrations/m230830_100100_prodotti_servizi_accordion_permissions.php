<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m230830_100100_prodotti_servizi_accordion_permissions */
class m230830_100100_prodotti_servizi_accordion_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' =>  'PRODOTTISERVIZIACCORDION_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model ProdottiServiziAccordion',
                'ruleName' => null,
                'parent' => ['PRODOTTISERVIZI_ADMIN']
            ],
            [
                'name' =>  'PRODOTTISERVIZIACCORDION_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model ProdottiServiziAccordion',
                'ruleName' => null,
                'parent' => [
                    'PRODOTTISERVIZI_ADMIN',
                    'PRODOTTISERVIZI_READER'
                ]
            ],
            [
                'name' =>  'PRODOTTISERVIZIACCORDION_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model ProdottiServiziAccordion',
                'ruleName' => null,
                'parent' => ['PRODOTTISERVIZI_ADMIN']
            ],
            [
                'name' =>  'PRODOTTISERVIZIACCORDION_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model ProdottiServiziAccordion',
                'ruleName' => null,
                'parent' => ['PRODOTTISERVIZI_ADMIN']
            ],
        ];
    }
}