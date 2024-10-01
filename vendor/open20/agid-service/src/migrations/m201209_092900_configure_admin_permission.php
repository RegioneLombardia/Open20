<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m201209_092900_configure_admin_permission
 */
class m201209_092900_configure_admin_permission extends AmosMigrationPermissions
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
                'name' => 'AGIDSERVICE_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_SERVICE_ADMIN']
                ]
            ],
            [
                'name' => 'AGIDSERVICE_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_SERVICE_ADMIN']
                ]
            ],
            [
                'name' => 'AGIDSERVICE_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_SERVICE_ADMIN']
                ]
            ],
            [
                'name' => 'AGIDSERVICE_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_SERVICE_ADMIN']
                ]
            ]

		];
    }
    
}
