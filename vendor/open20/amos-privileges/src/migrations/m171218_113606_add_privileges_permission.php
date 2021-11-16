<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\privileges\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m171218_113606_add_privileges_permission
 */
class m171218_113606_add_privileges_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'PRIVILEGES_MANAGER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Manager role for privileges',
                'parent' => ['ADMIN']
            ]
        ];
    }
}
