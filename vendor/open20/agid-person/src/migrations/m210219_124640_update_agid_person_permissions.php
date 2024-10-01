<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m210219_124640_update_agid_person_permissions*/
class m210219_124640_update_agid_person_permissions extends AmosMigrationPermissions
{
    const AGID_PERSON_WORKFLOW = 'AgidPersonWorkflow';
    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'AGIDPERSONORGANIZATIONALUNITMM_CREATE',
                    'update' => true,
                    'newValues' => [
                        'addParents' => ['AGID_PERSON_ADMIN']
                    ]
                ],
                [
                    'name' =>  'AGIDPERSONORGANIZATIONALUNITMM_READ',
                    'update' => true,
                    'newValues' => [
                        'addParents' => ['AGID_PERSON_ADMIN']
                    ]
                ],
                [
                    'name' =>  'AGIDPERSONORGANIZATIONALUNITMM_UPDATE',
                    'update' => true,
                    'newValues' => [
                        'addParents' => ['AGID_PERSON_ADMIN']
                    ]
                ],
                [
                    'name' =>  'AGIDPERSONORGANIZATIONALUNITMM_DELETE',
                    'update' => true,
                    'newValues' => [
                        'addParents' => ['AGID_PERSON_ADMIN']
                    ]
                ],
                [
                    'name' => self::AGID_PERSON_WORKFLOW . '/DRAFT',
                    'update' => true,
                    'newValues' => [
                        'addParents' => ['AGID_PERSON_ADMIN']
                    ]
                ],
                [
                    'name' => self::AGID_PERSON_WORKFLOW . '/VALIDATED',
                    'update' => true,
                    'newValues' => [
                        'addParents' => ['AGID_PERSON_ADMIN']
                    ]
                ],
                [
                    'name' =>  'AGIDPERSONTYPE_CREATE',
                    'update' => true,
                    'newValues' => [
                        'addParents' => ['AGID_PERSON_ADMIN']
                    ]
                ],
                [
                    'name' =>  'AGIDPERSONTYPE_READ',
                    'update' => true,
                    'newValues' => [
                        'addParents' => ['AGID_PERSON_ADMIN']
                    ]
                ],
                [
                    'name' =>  'AGIDPERSONTYPE_UPDATE',
                    'update' => true,
                    'newValues' => [
                        'addParents' => ['AGID_PERSON_ADMIN']
                    ]
                ],
                [
                    'name' =>  'AGIDPERSONTYPE_DELETE',
                    'update' => true,
                    'newValues' => [
                        'addParents' => ['AGID_PERSON_ADMIN']
                    ]
                ],
                [
                    'name' =>  'AGIDPERSONCONTENTTYPE_CREATE',
                    'update' => true,
                    'newValues' => [
                        'addParents' => ['AGID_PERSON_ADMIN']
                    ]
                ],
                [
                    'name' =>  'AGIDPERSONCONTENTTYPE_READ',
                    'update' => true,
                    'newValues' => [
                        'addParents' => ['AGID_PERSON_ADMIN']
                    ]
                ],
                [
                    'name' =>  'AGIDPERSONCONTENTTYPE_UPDATE',
                    'update' => true,
                    'newValues' => [
                        'addParents' => ['AGID_PERSON_ADMIN']
                    ]
                ],
                [
                    'name' =>  'AGIDPERSONCONTENTTYPE_DELETE',
                    'update' => true,
                    'newValues' => [
                        'addParents' => ['AGID_PERSON_ADMIN']
                    ]
                ],

            ];
    }
}
