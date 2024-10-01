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
class m190207_095324_add_permission_ticket_manager extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'TICKET_MANAGER_FOR_COMMUNITY',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to manage tickets, categories and faq',
                'ruleName' => TicketManagerInCommunityRoleRule::className(),
                'parent' => ['VALIDATED_BASIC_USER'],
                'children' => [
                    'TICKET_CREATE',
                    'TICKET_READ',
                    'TICKET_UPDATE',
                    'TICKET_DELETE',
                    'TICKETCATEGORIE_CREATE',
                    'TICKETCATEGORIE_READ',
                    'TICKETCATEGORIE_UPDATE',
                    'TICKETCATEGORIE_DELETE',
                    'TICKETFAQ_CREATE',
                    'TICKETFAQ_READ',
                    'TICKETFAQ_UPDATE',
                    'TICKETFAQ_DELETE',
                    TicketToProcessingWorkflowRule::className(),
                    TicketDeleteRule::className()
                ]
            ]
        ];
    }
}
