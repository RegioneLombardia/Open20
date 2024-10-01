<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m201209_092100_add_admin_permission
 */
class m201209_092100_add_admin_permission extends AmosMigrationPermissions
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
                'name' => 'AGIDPERSON_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_PERSON_ADMIN']
                ]
            ],
            [
                'name' => 'AGIDPERSON_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_PERSON_ADMIN']
                ]
            ],
            [
                'name' => 'AGIDPERSON_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_PERSON_ADMIN']
                ]
            ],
            [
                'name' => 'AGIDPERSON_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AGID_PERSON_ADMIN']
                ]
            ]

		];
    }
    
}
