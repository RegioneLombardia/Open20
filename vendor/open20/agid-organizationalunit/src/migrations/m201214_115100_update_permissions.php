<?php


/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

class m201214_115100_update_permissions extends AmosMigrationPermissions
{
    protected function setRBACConfigurations()
    {
        return [

            [
                'name' => 'AGIDORGANIZATIONALUNITTYPE_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'AGID_ORGANIZATIONAL_UNIT_ADMIN'
                    ]
                ]
            ],
            [
                'name' => 'AGIDORGANIZATIONALUNITTYPE_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'AGID_ORGANIZATIONAL_UNIT_ADMIN'
                    ]
                ]
            ],
            [
                'name' => 'AGIDORGANIZATIONALUNITTYPE_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'AGID_ORGANIZATIONAL_UNIT_ADMIN'
                    ]
                ]
            ],
            [
                'name' => 'AGIDORGANIZATIONALUNITTYPE_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'AGID_ORGANIZATIONAL_UNIT_ADMIN'
                    ]
                ]
            ],

            //
            [
                'name' => 'AGIDORGANIZATIONALUNITCONTENTTYPE_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'AGID_ORGANIZATIONAL_UNIT_ADMIN'
                    ]
                ]
            ],
            [
                'name' => 'AGIDORGANIZATIONALUNITCONTENTTYPE_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'AGID_ORGANIZATIONAL_UNIT_ADMIN'
                    ]
                ]
            ],
            [
                'name' => 'AGIDORGANIZATIONALUNITCONTENTTYPE_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'AGID_ORGANIZATIONAL_UNIT_ADMIN'
                    ]
                ]
            ],
            [
                'name' => 'AGIDORGANIZATIONALUNITCONTENTTYPE_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'AGID_ORGANIZATIONAL_UNIT_ADMIN'
                    ]
                ]
            ],

            
            //
            [
                'name' => 'AGIDORGANIZATIONALUNIT_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'AGID_ORGANIZATIONAL_UNIT_ADMIN'
                    ]
                ]
            ],
            [
                'name' => 'AGIDORGANIZATIONALUNIT_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'AGID_ORGANIZATIONAL_UNIT_ADMIN'
                    ]
                ]
            ],
            [
                'name' => 'AGIDORGANIZATIONALUNIT_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'AGID_ORGANIZATIONAL_UNIT_ADMIN'
                    ]
                ]
            ],            
            [
                'name' => 'AGIDORGANIZATIONALUNIT_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'AGID_ORGANIZATIONAL_UNIT_ADMIN'
                    ]
                ]
            ],


            // 
            [
                'name' => 'AGIDORGANIZATIONALUNITSERVICEMM_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'AGID_ORGANIZATIONAL_UNIT_ADMIN'
                    ]
                ]
            ],
            [
                'name' => 'AGIDORGANIZATIONALUNITSERVICEMM_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'AGID_ORGANIZATIONAL_UNIT_ADMIN'
                    ]
                ]
            ],
            [
                'name' => 'AGIDORGANIZATIONALUNITSERVICEMM_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'AGID_ORGANIZATIONAL_UNIT_ADMIN'
                    ]
                ]
            ],
            [
                'name' => 'AGIDORGANIZATIONALUNITSERVICEMM_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'AGID_ORGANIZATIONAL_UNIT_ADMIN'
                    ]
                ]
            ],


            //
            [
                'name' => 'AGIDORGANIZATIONALORGANIZATIONALUNITMM_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'AGID_ORGANIZATIONAL_UNIT_ADMIN'
                    ]
                ]
            ],
            [
                'name' => 'AGIDORGANIZATIONALORGANIZATIONALUNITMM_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'AGID_ORGANIZATIONAL_UNIT_ADMIN'
                    ]
                ]
            ],
            [
                'name' => 'AGIDORGANIZATIONALORGANIZATIONALUNITMM_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'AGID_ORGANIZATIONAL_UNIT_ADMIN'
                    ]
                ]
            ],
            [
                'name' => 'AGIDORGANIZATIONALORGANIZATIONALUNITMM_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'AGID_ORGANIZATIONAL_UNIT_ADMIN'
                    ]
                ]
            ],

            //
            [
                'name' => 'AGID_ORGANIZATIONAL_UNIT_ADMIN',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'UO'
                    ]
                ]
            ],
        ];
    }
}
