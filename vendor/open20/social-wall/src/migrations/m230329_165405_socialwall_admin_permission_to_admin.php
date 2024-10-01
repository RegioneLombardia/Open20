<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m230329_165405_socialwall_admin_permission_to_admin */
class m230329_165405_socialwall_admin_permission_to_admin extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'SOCIALWALL_ADMIN',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di SOCIALWALL_ADMIN ad ADMIN',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
        ];
    }
}
