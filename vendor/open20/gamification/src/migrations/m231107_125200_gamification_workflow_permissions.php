<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

class m231107_125200_gamification_workflow_permissions extends AmosMigrationPermissions
{
    const GAMIFICATION_WORKFLOW_NAME = 'GamificationWorkflow';

    /**
     * Use this function to map permissions, roles and associations between permissions and roles. If you don't need to
     * to add or remove any permissions or roles you have to delete this method.
     */
    protected function setAuthorizations()
    {
        $this->authorizations = [
            [
                'name' => self::GAMIFICATION_WORKFLOW_NAME . '/DRAFT',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Workflow status permission: Draft',
                'ruleName' => null,
                'parent' => ['ADMIN', 'SUPERUSER', 'GAMIFICATION_ADMINISTRATOR']
            ],
            [
                'name' => self::GAMIFICATION_WORKFLOW_NAME . '/OPENING',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Workflow status permission: Opening',
                'ruleName' => null,
                'parent' => ['ADMIN', 'SUPERUSER', 'GAMIFICATION_ADMINISTRATOR']
            ],
            [
                'name' => self::GAMIFICATION_WORKFLOW_NAME . '/CONCLUDED',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Workflow status permission: Concluded',
                'ruleName' => null,
                'parent' => ['ADMIN', 'SUPERUSER', 'GAMIFICATION_ADMINISTRATOR']
            ]
        ];
    }
}
