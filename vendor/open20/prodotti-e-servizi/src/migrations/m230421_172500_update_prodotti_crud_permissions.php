<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m230421_172500_update_prodotti_crud_permissions*/
class m230421_172500_update_prodotti_crud_permissions extends AmosMigrationPermissions
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
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'REDATTORECMS',
                        'CAPOREDATTORECMS'
                    ],
                ]
            ],
            [
                'name' => 'PRODOTTISOLUZIONI_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model ProdottiSoluzioni',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'REDATTORECMS',
                        'CAPOREDATTORECMS'
                    ],
                ]
            ],
            [
                'name' => 'PRODOTTISOLUZIONI_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model ProdottiSoluzioni',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'REDATTORECMS',
                        'CAPOREDATTORECMS'
                    ],
                ]
            ],

            [
                'name' =>  'PRODOTTIFINALITA_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model ProdottiFinalita',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'REDATTORECMS',
                        'CAPOREDATTORECMS'
                    ],
                ]
            ],
            [
                'name' =>  'PRODOTTIFINALITA_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model ProdottiFinalita',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'REDATTORECMS',
                        'CAPOREDATTORECMS'
                    ],
                ]
            ],
            [
                'name' =>  'PRODOTTIFINALITA_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model ProdottiFinalita',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'REDATTORECMS',
                        'CAPOREDATTORECMS'
                    ],
                ]
            ],

            [
                'name' =>  'PRODOTTIDESTINATARI_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model ProdottiDestinatari',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'REDATTORECMS',
                        'CAPOREDATTORECMS'
                    ],
                ]
            ],
            [
                'name' =>  'PRODOTTIDESTINATARI_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model ProdottiDestinatari',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'REDATTORECMS',
                        'CAPOREDATTORECMS'
                    ],
                ]
            ],
            [
                'name' =>  'PRODOTTIDESTINATARI_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model ProdottiDestinatari',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'REDATTORECMS',
                        'CAPOREDATTORECMS'
                    ],
                ]
            ],

            [
                'name' =>  'PRODOTTISERVIZI_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model ProdottiServizi',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'REDATTORECMS',
                        'CAPOREDATTORECMS'
                    ],
                ]
            ],
            [
                'name' =>  'PRODOTTISERVIZI_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model ProdottiServizi',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'REDATTORECMS',
                        'CAPOREDATTORECMS'
                    ],
                ]
            ],
            [
                'name' =>  'PRODOTTISERVIZI_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model ProdottiServizi',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'REDATTORECMS',
                        'CAPOREDATTORECMS'
                    ],
                ]
            ],
        ];
    }
}