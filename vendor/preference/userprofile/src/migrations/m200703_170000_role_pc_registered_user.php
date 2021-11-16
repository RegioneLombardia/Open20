<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m200703_170000_role_pc_registered_user*/
class m200703_170000_role_pc_registered_user extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'PC_REGISTERD_USER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo per gli utenti registrati',
                'ruleName' => null,
                'parent' => [],
            ],

        ];
    }
}
