<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m220516_165154_user_access_log_permissions*/
class m220516_165154_user_access_log_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'USER_ACCESS_LOG_ADMIN',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model UserAccessLog',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'USERACCESSLOG_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model UserAccessLog',
                'ruleName' => null,
                'parent' => ['USER_ACCESS_LOG_ADMIN']
            ],
            [
                'name' => 'USERACCESSLOG_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model UserAccessLog',
                'ruleName' => null,
                'parent' => ['USER_ACCESS_LOG_ADMIN']
            ],
            [
                'name' => 'USERACCESSLOG_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model UserAccessLog',
                'ruleName' => null,
                'parent' => ['USER_ACCESS_LOG_ADMIN']
            ],
            [
                'name' => 'USERACCESSLOG_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model UserAccessLog',
                'ruleName' => null,
                'parent' => ['USER_ACCESS_LOG_ADMIN']
            ],

        ];
    }
}
