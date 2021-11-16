<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m210219_113740_update_agid_service_permissions*/
class m210219_113740_update_agid_service_permissions extends AmosMigrationPermissions
{
    const AGID_SERVICE_WORKFLOW = 'AgidServiceWorkflow';
    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' =>  'AGIDSERVICERELATEDSERVICEMM_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_SERVICE_ADMIN']
                ]
            ],
            [
                'name' =>  'AGIDSERVICERELATEDSERVICEMM_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_SERVICE_ADMIN']
                ]
            ],
            [
                'name' =>  'AGIDSERVICERELATEDSERVICEMM_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_SERVICE_ADMIN']
                ]
            ],
            [
                'name' =>  'AGIDSERVICERELATEDSERVICEMM_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_SERVICE_ADMIN']
                ]
            ],
            [
                'name' =>  'AGIDSERVICEDOCUMENTIMM_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_SERVICE_ADMIN']
                ]
            ],
            [
                'name' =>  'AGIDSERVICEDOCUMENTIMM_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_SERVICE_ADMIN']
                ]
            ],
            [
                'name' =>  'AGIDSERVICEDOCUMENTIMM_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_SERVICE_ADMIN']
                ]
            ],
            [
                'name' =>  'AGIDSERVICEDOCUMENTIMM_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_SERVICE_ADMIN']
                ]
            ],
            [
                'name' => self::AGID_SERVICE_WORKFLOW . '/DRAFT',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_SERVICE_ADMIN']
                ]
            ],
            [
                'name' => self::AGID_SERVICE_WORKFLOW . '/VALIDATED',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_SERVICE_ADMIN']
                ]
            ],
            [
                'name' =>  'AGIDCONTENTTYPESERVICE_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_SERVICE_ADMIN']
                ]
            ],
            [
                'name' =>  'AGIDCONTENTTYPESERVICE_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_SERVICE_ADMIN']
                ]
            ],
            [
                'name' =>  'AGIDCONTENTTYPESERVICE_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_SERVICE_ADMIN']
                ]
            ],
            [
                'name' =>  'AGIDCONTENTTYPESERVICE_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_SERVICE_ADMIN']
                ]
            ],
            [
                'name' =>  'AGIDSERVICESTATUS_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_SERVICE_ADMIN']
                ]
            ],
            [
                'name' =>  'AGIDSERVICESTATUS_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_SERVICE_ADMIN']
                ]
            ],
            [
                'name' =>  'AGIDSERVICESTATUS_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_SERVICE_ADMIN']
                ]
            ],
            [
                'name' =>  'AGIDSERVICESTATUS_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_SERVICE_ADMIN']
                ]
            ],
            [
                'name' =>  'AGIDSERVICETYPE_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_SERVICE_ADMIN']
                ]
            ],
            [
                'name' =>  'AGIDSERVICETYPE_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_SERVICE_ADMIN']
                ]
            ],
            [
                'name' =>  'AGIDSERVICETYPE_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_SERVICE_ADMIN']
                ]
            ],
            [
                'name' =>  'AGIDSERVICETYPE_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_SERVICE_ADMIN']
                ]
            ],
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
          
            ];
    }
}
