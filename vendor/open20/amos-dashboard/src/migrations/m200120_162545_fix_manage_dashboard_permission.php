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

/**
 * Class m200120_162545_fix_manage_dashboard_permission
 */
class m200120_162545_fix_manage_dashboard_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'CAN_MANAGE_DASHBOARD',
                'update' => true,
                'newValues' => [
                    'addParents' => ['ADMIN']
                ]
            ]
        ];
    }
}
