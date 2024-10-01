<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m211124_091358_populate_superuser_role_for_community
 */
class m211124_091358_populate_superuser_role_for_community extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'AMMINISTRATORE_COMMUNITY',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SUPERUSER']
                ]
            ]
        ];
    }
}

