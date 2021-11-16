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
 * Class m180417_125422_add_change_userprofile_workflow_status_permissions
 */
class m180417_125422_add_change_userprofile_workflow_status_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'CHANGE_USERPROFILE_WORKFLOW_STATUS',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to change user profile workflow status',
                'parent' => ['ADMIN', 'BASIC_USER']
            ]
        ];
    }
}
