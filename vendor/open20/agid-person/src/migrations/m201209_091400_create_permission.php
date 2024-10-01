<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m201209_091400_create_permission
 */
class m201209_091400_create_permission extends AmosMigrationPermissions
{

    /**
     * migration for permission of AGID PERSON
     *
     * @return array
     */
    protected function setRBACConfigurations()
    {
		return [
			[
				'name' => 'AGID_PERSON_ADMIN',
				'type' => Permission::TYPE_ROLE,
				'description' => 'Administratore sulla gestione di AGID PERSON',
				'ruleName' => null,
                'parent' => ['ADMIN'],
			]
		];
    }

}
