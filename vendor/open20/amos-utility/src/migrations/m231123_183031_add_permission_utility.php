<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m231123_183031_add_permission_utility*/
class m231123_183031_add_permission_utility extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the Utility Module';

        return [
            [
                'name' => 'ACCESS_THE_BASEMENT',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Role to Access The Basement',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],

        ];
    }
}
