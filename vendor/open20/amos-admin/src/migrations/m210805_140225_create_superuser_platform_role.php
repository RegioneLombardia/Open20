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
 * Class m210805_140225_create_superuser_platform_role
 */
class m210805_140225_create_superuser_platform_role extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'SUPERUSER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Super User role'
            ]
        ];
    }
}
