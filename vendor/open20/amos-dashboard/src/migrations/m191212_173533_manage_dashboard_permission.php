<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

class m191212_173533_manage_dashboard_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'CAN_MANAGE_DASHBOARD',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso gestione widget della dashboard',
                'parent' => [
                    'BASIC_USER'
                ],
            ],
        ];
    }
}
