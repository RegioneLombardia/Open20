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
 * Class m190424_110048_add_admin_basic_lists_permission
 */
class m190424_110048_add_admin_basic_lists_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'USER_PROFILE_BASIC_LIST_ACTIONS',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to see the basic admin plugin lists',
                'parent' => ['AMMINISTRATORE_UTENTI', 'BASIC_USER']
            ]
        ];
    }
}
