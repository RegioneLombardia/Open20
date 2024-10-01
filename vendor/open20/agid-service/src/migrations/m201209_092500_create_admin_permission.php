<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m201209_092500_create_admin_permission
 */
class m201209_092500_create_admin_permission extends AmosMigrationPermissions
{

    /**
     * migration for status of AGID SERVICE
     *
     * @return array
     */
    protected function setRBACConfigurations()
    {
		return [
			[
				'name' => 'AGID_SERVICE_ADMIN',
				'type' => Permission::TYPE_ROLE,
				'description' => 'Administratore sulla gestione di AGID SERVICE',
				'ruleName' => null,
                'parent' => ['ADMIN'],
			]
		];
    }

}
