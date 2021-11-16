<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;

/**
 * Class m201217_190033_fix_news_permissions
 */
class m201217_190033_fix_news_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'NEWS_PUBLISHER_FRONTEND',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSCONTENTTYPE_CREATE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSCONTENTTYPE_READ',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSCONTENTTYPE_UPDATE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSCONTENTTYPE_DELETE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSRELATEDAGIDSERVICEMM_CREATE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSRELATEDAGIDSERVICEMM_READ',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSRELATEDAGIDSERVICEMM_UPDATE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSRELATEDAGIDSERVICEMM_DELETE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSRELATEDDOCUMENTIMM_CREATE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSRELATEDDOCUMENTIMM_READ',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSRELATEDDOCUMENTIMM_UPDATE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSRELATEDDOCUMENTIMM_DELETE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSRELATEDNEWSMM_CREATE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSRELATEDNEWSMM_READ',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSRELATEDNEWSMM_UPDATE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSRELATEDNEWSMM_DELETE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSAGIDPERSONMM_CREATE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSAGIDPERSONMM_READ',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSAGIDPERSONMM_UPDATE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ],
            [
                'name' => 'NEWSAGIDPERSONMM_DELETE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ADMIN'],
                    'addParents' => ['AMMINISTRATORE_NEWS']
                ]
            ]
        ];
    }
}
