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
 * Class m200323_141722_permissions
 */
class m200322_151722_add_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [

            [
                'name' => 'SOCIAL',
                'type' => Permission::TYPE_ROLE,
                'description' => 'SUPER USER role'
            ],
            [
                'name' => 'SUPER_USER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'SUPER USER role'
            ],
            [
                'name' => 'GUEST',
                'type' => Permission::TYPE_ROLE,
                'description' => 'SUPER USER role'
            ],
        ];
    }
}