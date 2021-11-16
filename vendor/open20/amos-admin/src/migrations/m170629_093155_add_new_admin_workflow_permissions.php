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
 * Class m170629_093155_add_new_admin_workflow_permissions
 */
class m170629_093155_add_new_admin_workflow_permissions extends AmosMigrationPermissions
{
    const WORKFLOW_NAME = 'UserProfileWorkflow';
    
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => self::WORKFLOW_NAME . '/DRAFT',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'UserProfileWorkflow status permission: Draft',
                'parent' => ['ADMIN', 'BASIC_USER']
            ],
            [
                'name' => self::WORKFLOW_NAME . '/TOVALIDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'UserProfileWorkflow status permission: To validate',
                'parent' => ['ADMIN', 'BASIC_USER', 'FACILITATOR']
            ],
            [
                'name' => self::WORKFLOW_NAME . '/VALIDATED',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'UserProfileWorkflow status permission: Validated',
                'parent' => ['ADMIN', 'FACILITATOR']
            ]
        ];
    }
}
