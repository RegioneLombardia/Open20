<?php

use yii\rbac\Permission;

class m230518_165500_add_prodotti_e_servizi_roles_and_permissions extends \open20\amos\core\migration\AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';
        return [
            [
                'name' => 'PRODOTTISERVIZI_ADMIN',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo che permette di amministrare i prodotti-servizi',
                'ruleName' => null,
                'parent' => [
                    'ADMIN',
                ]
            ],
            [
                'name' => 'PRODOTTISERVIZI_READER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo che permette di vedere i prodotti-servizi',
                'ruleName' => null,
                'parent' => [
                    'BASIC_USER'
                ]
            ],
            [
                'name' => 'PRODOTTIDESTINATARI_ADMIN',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo che permette di amministrare i prodotti-destinatari',
                'ruleName' => null,
                'parent' => [
                    'ADMIN',
                ]
            ],
            [
                'name' => 'PRODOTTIDESTINATARI_READER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo che permette di vedere i prodotti-destinatari',
                'ruleName' => null,
                'parent' => [
                ]
            ],
            [
                'name' => 'PRODOTTISOLUZIONI_ADMIN',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo che permette di amministrare i prodotti-soluzioni',
                'ruleName' => null,
                'parent' => [
                    'ADMIN',
                ]
            ],
            [
                'name' => 'PRODOTTISOLUZIONI_READER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo che permette di vedere i prodotti-soluzioni',
                'ruleName' => null,
                'parent' => [
                ]
            ],
            [
                'name' => 'PRODOTTIFINALITA_ADMIN',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo che permette di amministrare i prodotti-finalita',
                'ruleName' => null,
                'parent' => [
                    'ADMIN',
                ]
            ],
            [
                'name' => 'PRODOTTIFINALITA_READER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo che permette di vedere i prodotti-finalita',
                'ruleName' => null,
                'parent' => [
                ]
            ],

            // Permessi prodotti-servizi
            [
                'name' =>  'PRODOTTISERVIZI_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model ProdottiServizi',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'addParents' => ['PRODOTTISERVIZI_ADMIN'],
                    'removeParents' => [
                        'ADMIN',
                        'CAPOREDATTORECMS',
                        'REDATTORECMS'
                    ]
                ]
            ],
            [
                'name' =>  'PRODOTTISERVIZI_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model ProdottiServizi',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'addParents' => ['PRODOTTISERVIZI_ADMIN', 'PRODOTTISERVIZI_READER'],
                    'removeParents' => ['ADMIN']
                ]
            ],
            [
                'name' =>  'PRODOTTISERVIZI_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model ProdottiServizi',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'addParents' => ['PRODOTTISERVIZI_ADMIN'],
                    'removeParents' => [
                        'ADMIN',
                        'CAPOREDATTORECMS',
                        'REDATTORECMS'
                    ]
                ]
            ],
            [
                'name' =>  'PRODOTTISERVIZI_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model ProdottiServizi',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'addParents' => ['PRODOTTISERVIZI_ADMIN'],
                    'removeParents' => [
                        'ADMIN',
                        'CAPOREDATTORECMS',
                        'REDATTORECMS'
                    ]
                ]
            ],

            // Permessi prodotti-destinatari
            [
                'name' =>  'PRODOTTIDESTINATARI_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model ProdottiDestinatari',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'addParents' => ['PRODOTTIDESTINATARI_ADMIN'],
                    'removeParents' => ['ADMIN']
                ]
            ],
            [
                'name' =>  'PRODOTTIDESTINATARI_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model ProdottiDestinatari',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'addParents' => ['PRODOTTIDESTINATARI_ADMIN'],
                    'removeParents' => ['ADMIN']
                ]
            ],
            [
                'name' =>  'PRODOTTIDESTINATARI_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model ProdottiDestinatari',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'addParents' => ['PRODOTTIDESTINATARI_ADMIN'],
                    'removeParents' => ['ADMIN']
                ]
            ],
            [
                'name' =>  'PRODOTTIDESTINATARI_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model ProdottiDestinatari',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'addParents' => ['PRODOTTIDESTINATARI_ADMIN'],
                    'removeParents' => ['ADMIN']
                ]
            ],

            // Permessi prodotti-soluzioni
            [
                'name' => 'PRODOTTISOLUZIONI_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model ProdottiSoluzioni',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'addParents' => ['PRODOTTISOLUZIONI_ADMIN'],
                    'removeParents' => ['ADMIN']
                ]
            ],
            [
                'name' => 'PRODOTTISOLUZIONI_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model ProdottiSoluzioni',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'addParents' => ['PRODOTTISOLUZIONI_ADMIN'],
                    'removeParents' => ['ADMIN']
                ]
            ],
            [
                'name' => 'PRODOTTISOLUZIONI_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model ProdottiSoluzioni',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'addParents' => ['PRODOTTISOLUZIONI_ADMIN'],
                    'removeParents' => ['ADMIN']
                ]
            ],
            [
                'name' => 'PRODOTTISOLUZIONI_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model ProdottiSoluzioni',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'addParents' => ['PRODOTTISOLUZIONI_ADMIN'],
                    'removeParents' => ['ADMIN']
                ]
            ],

            // Permessi prodotti-finalita
            [
                'name' =>  'PRODOTTIFINALITA_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model ProdottiFinalita',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'addParents' => ['PRODOTTIFINALITA_ADMIN'],
                    'removeParents' => ['ADMIN']
                ]
            ],
            [
                'name' =>  'PRODOTTIFINALITA_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model ProdottiFinalita',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'addParents' => ['PRODOTTIFINALITA_ADMIN'],
                    'removeParents' => ['ADMIN']
                ]
            ],
            [
                'name' =>  'PRODOTTIFINALITA_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model ProdottiFinalita',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'addParents' => ['PRODOTTIFINALITA_ADMIN'],
                    'removeParents' => ['ADMIN']
                ]
            ],
            [
                'name' =>  'PRODOTTIFINALITA_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model ProdottiFinalita',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'addParents' => ['PRODOTTIFINALITA_ADMIN'],
                    'removeParents' => ['ADMIN']
                ]
            ],
        ];
    }
}