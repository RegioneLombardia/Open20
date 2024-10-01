<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m211124_110228_populate_superuser_role_for_admin
 */
class m211124_110228_populate_superuser_role_for_admin extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'AMMINISTRATORE_UTENTI',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SUPERUSER']
                ]
            ]
        ];
    }
}