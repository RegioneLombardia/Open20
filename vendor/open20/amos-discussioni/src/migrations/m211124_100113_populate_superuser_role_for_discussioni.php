<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m211124_100113_populate_superuser_role_for_discussioni
 */
class m211124_100113_populate_superuser_role_for_discussioni extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'AMMINISTRATORE_DISCUSSIONI',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SUPERUSER']
                ]
            ]
        ];
    }
}