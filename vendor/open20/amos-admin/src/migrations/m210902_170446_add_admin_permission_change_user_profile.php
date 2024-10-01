<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m210902_170446_add_admin_permission_change_user_profile
 */
class m210902_170446_add_admin_permission_change_user_profile extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'CHANGE_USER_PROFILE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => "Permission to change your logged user",
                'parent' => ['ADMIN', 'AMMINISTRATORE_UTENTI', 'BASIC_USER']
            ],
            [
                'name' => 'USER_CAN_CHANGE_FISCAL_CODE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => "Permission to change your logged user",
                'parent' => ['ADMIN', 'AMMINISTRATORE_UTENTI']
            ]
        ];
    }
}
