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
 * Class m180611_115155_userprofile_notvalidated_workflow_status_permissions
 */
class m180611_115155_userprofile_notvalidated_workflow_status_permissions extends AmosMigrationPermissions
{
    const WORKFLOW_NAME = 'UserProfileWorkflow';
    
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => self::WORKFLOW_NAME . '/NOTVALIDATED',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'UserProfileWorkflow status permission: Not Validated',
                'parent' => ['ADMIN', 'FACILITATOR']
            ]
        ];
    }
}
