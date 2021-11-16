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
 * Class m170623_133028_add_admin_facilitator_role
 */
class m170623_133028_add_admin_facilitator_role extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'FACILITATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Facilitator platform role'
            ]
        ];
    }
}
