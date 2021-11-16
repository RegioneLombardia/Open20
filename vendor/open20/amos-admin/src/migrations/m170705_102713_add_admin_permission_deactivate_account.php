<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m170705_102713_add_admin_permission_deactivate_account
 */
class m170705_102713_add_admin_permission_deactivate_account extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'DeactivateAccount',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to deactivate a user account',
                'parent' => ['ADMIN', 'UpdateOwnUserProfile']
            ]
        ];
    }
}
