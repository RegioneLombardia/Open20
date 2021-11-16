<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

class m170322_165328_event_workflow_permissions extends AmosMigrationPermissions
{
    const EVENT_WORKFLOW_NAME = 'EventWorkflow';

    /**
     * Use this function to map permissions, roles and associations between permissions and roles. If you don't need to
     * to add or remove any permissions or roles you have to delete this method.
     */
    protected function setAuthorizations()
    {
        $this->authorizations = [
            [
                'name' => self::EVENT_WORKFLOW_NAME . '/DRAFT',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Workflow status permission: Draft',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR', 'EVENTS_CREATOR', 'EVENTS_VALIDATOR', 'PLATFORM_EVENTS_VALIDATOR']
            ],
            [
                'name' => self::EVENT_WORKFLOW_NAME . '/PUBLISHREQUEST',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Workflow status permission: Publish request',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR', 'EVENTS_CREATOR', 'EVENTS_VALIDATOR', 'PLATFORM_EVENTS_VALIDATOR']
            ],
            [
                'name' => self::EVENT_WORKFLOW_NAME . '/PUBLISHED',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Workflow status permission: Published',
                'ruleName' => null,
                'parent' => ['EVENTS_ADMINISTRATOR', 'EVENTS_VALIDATOR', 'PLATFORM_EVENTS_VALIDATOR']
            ]
        ];
    }
}
