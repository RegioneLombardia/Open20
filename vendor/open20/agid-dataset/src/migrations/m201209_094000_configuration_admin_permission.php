<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m201209_094000_configuration_admin_permission
 */
class m201209_094000_configuration_admin_permission extends AmosMigrationPermissions
{

    /**
     * migration for permission for AGID PERSON
     *
     * @return array
     */
    protected function setRBACConfigurations()
    {

		return [

            [
                'name' => 'AGIDDATASETTYPE_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_DATASET_ADMIN']
                ]
            ],
            [
                'name' => 'AGIDDATASETTYPE_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_DATASET_ADMIN']
                ]
            ],
            [
                'name' => 'AGIDDATASETTYPE_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_DATASET_ADMIN']
                ]
            ],
            [
                'name' => 'AGIDDATASETTYPE_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_DATASET_ADMIN']
                ]
            ]

		];
    }
    
}
