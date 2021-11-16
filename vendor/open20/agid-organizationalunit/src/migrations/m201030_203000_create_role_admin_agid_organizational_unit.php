<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m201030_203000_create_role_admin_agid_organizational_unit
 */
class m201030_203000_create_role_admin_agid_organizational_unit extends AmosMigrationPermissions
{

    /**
     * migration for status of AGID ORGANIZATIONAL UNIT
     *
     * @return array
     */
    protected function setRBACConfigurations()
    {
		return [
			[
				'name' => 'AGID_ORGANIZATIONAL_UNIT_ADMIN',
				'type' => Permission::TYPE_ROLE,
				'description' => 'Administratore sulla gestione di AGID ORGANIZATIONAL UNIT',
				'ruleName' => null,
                'parent' => ['ADMIN'],
			]
		];
    }

}
