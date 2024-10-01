<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open2\amos\ticket\rules\TicketDeleteRule;
use open2\amos\ticket\rules\TicketManagerInCommunityRoleRule;
use open2\amos\ticket\rules\workflow\TicketToProcessingWorkflowRule;
use yii\rbac\Permission;

/**
 * Class m190207_095324_add_permission_ticket_manager
 */
class m190531_155294_add_permission_export extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'TICKET_EXPORT',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Export tickets',
                'parent' => ['ADMIN', TicketManagerInCommunityRoleRule::className()],
            ]
        ];
    }
}
