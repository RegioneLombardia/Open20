<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m211124_100804_populate_superuser_role_for_documenti
 */
class m211124_100804_populate_superuser_role_for_documenti extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'AMMINISTRATORE_DOCUMENTI',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SUPERUSER']
                ]
            ]
        ];
    }
}