<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m201214_120800_update_permission_agid_dataset_admin
 */
class m201214_120800_update_permission_agid_dataset_admin extends AmosMigrationPermissions
{

    /**
     * migration for permission of AGID DATASET
     *
     * @return array
     */
    protected function setRBACConfigurations()
    {
		return [

            //
            [
                'name' => 'AGIDDATASETCONTENTTYPE_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'AGID_DATASET_ADMIN'
                    ]
                ]
            ],
            [
                'name' => 'AGIDDATASETCONTENTTYPE_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'AGID_DATASET_ADMIN'
                    ]
                ]
            ],
            [
                'name' => 'AGIDDATASETCONTENTTYPE_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'AGID_DATASET_ADMIN'
                    ]
                ]
            ],
            [
                'name' => 'AGIDDATASETCONTENTTYPE_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'AGID_DATASET_ADMIN'
                    ]
                ]
            ],

            //
            [
                'name' => 'AGIDDATASET_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'AGID_DATASET_ADMIN'
                    ]
                ]
            ],
            [
                'name' => 'AGIDDATASET_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'AGID_DATASET_ADMIN'
                    ]
                ]
            ],
            [
                'name' => 'AGIDDATASET_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'AGID_DATASET_ADMIN'
                    ]
                ]
            ],
            [
                'name' => 'AGIDDATASET_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'AGID_DATASET_ADMIN'
                    ]
                ]
            ],


            // 
            [
                'name' => 'AGID_DATASET_ADMIN',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'DATASET'
                    ]
                ]
            ],


		];
    }

}
