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
 * Class m191113_091558_add_change_user_password_permission
 */
class m191113_091558_add_change_user_password_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'CHANGE_USER_PASSWORD',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per modificare la password utente',
                'parent' => ['ADMIN', 'BASIC_USER']
            ]
        ];
    }
}
