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
class m200322_141722_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'EVENT_DG',
                'type' => Permission::TYPE_ROLE,
                'description' => 'DG role'
            ],
            [
                'name' => 'EVENT_DG_OPERATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'OPERATOR DG role'
            ],

            [
                'name' => 'REGISTRATO_EVENTO',
                'type' => Permission::TYPE_ROLE,
                'description' => 'SUPER USER role'
            ],
        ];
    }
}