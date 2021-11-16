<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m201030_204000_create_permissions_agid_organizational_unit
 */
class m201030_204000_create_permissions_agid_organizational_unit extends AmosMigrationPermissions
{

    /**
     * migration for permission for AGID ORGANIZATIONAL UNIT
     *
     * @return array
     */
    protected function setRBACConfigurations()
    {

		return [
			[
				'name' => 'AGID_ORGANIZATIONAL_UNIT_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model AgidOrganizationalUnit',
                'ruleName' => null,
                'parent' => ['AGID_ORGANIZATIONAL_UNIT_ADMIN'],
			],
			[
				'name' => 'AGID_ORGANIZATIONAL_UNIT_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model AgidOrganizationalUnit',
                'ruleName' => null,
                'parent' => ['AGID_ORGANIZATIONAL_UNIT_ADMIN'],
			],
			[
				'name' => 'AGID_ORGANIZATIONAL_UNIT_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model AgidOrganizationalUnit',
                'ruleName' => null,
                'parent' => ['AGID_ORGANIZATIONAL_UNIT_ADMIN'],
			],
			[
				'name' => 'AGID_ORGANIZATIONAL_UNIT_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model AgidOrganizationalUnit',
                'ruleName' => null,
                'parent' => ['AGID_ORGANIZATIONAL_UNIT_ADMIN'],
			]
		];
    }
    
}
