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
use open2\amos\ticket\models\Ticket;
use open2\amos\ticket\rules\workflow\TicketWaitingTechnicalAssistanceWorkflowRule;
use yii\rbac\Permission;

/**
 * Class m211011_100753_add_ticket_workflow_status_waiting_technical_assistance_permissions
 */
class m211011_100753_add_ticket_workflow_status_waiting_technical_assistance_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => TicketWaitingTechnicalAssistanceWorkflowRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso regola workflow ticket stato in attesa di assistenza tecnica',
                'ruleName' => TicketWaitingTechnicalAssistanceWorkflowRule::className(),
                'parent' => ['OPERATORE_TICKET']
            ],
            [
                'name' => Ticket::TICKET_WORKFLOW_STATUS_WAITING_TECHNICAL_ASSISTANCE,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso workflow ticket stato in attesa di assistenza tecnica',
                'parent' => [TicketWaitingTechnicalAssistanceWorkflowRule::className()]
            ],
        ];
    }
}
