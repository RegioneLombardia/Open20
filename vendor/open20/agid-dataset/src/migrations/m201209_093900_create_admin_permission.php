<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m201209_093900_create_admin_permission
 */
class m201209_093900_create_admin_permission extends AmosMigrationPermissions
{

    /**
     * migration for permission of AGID DATASET
     *
     * @return array
     */
    protected function setRBACConfigurations()
    {
		return [
			[
				'name' => 'AGID_DATASET_ADMIN',
				'type' => Permission::TYPE_ROLE,
				'description' => 'Administratore sulla gestione di AGID DATASET',
				'ruleName' => null,
                'parent' => ['ADMIN'],
			]
		];
    }

}
