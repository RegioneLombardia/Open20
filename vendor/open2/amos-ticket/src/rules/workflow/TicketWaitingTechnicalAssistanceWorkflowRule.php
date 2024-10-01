<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\rules\workflow
 * @category   CategoryName
 */

namespace open2\amos\ticket\rules\workflow;

use open20\amos\core\rules\BasicContentRule;
use open2\amos\ticket\AmosTicket;
use open2\amos\ticket\models\Ticket;

/**
 * Class TicketWaitingTechnicalAssistanceWorkflowRule
 * @package open2\amos\ticket\rules\workflow
 */
class TicketWaitingTechnicalAssistanceWorkflowRule extends BasicContentRule
{
    public $name = 'ticketWaitingTechnicalAssistanceWorkflowRule';
    
    /**
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        /** @var AmosTicket $ticketModule */
        $ticketModule = AmosTicket::instance();
        
        // If the administrative ticket category type is disabled the permission is not available
        if (!$ticketModule->enableAdministrativeTicketCategory) {
            return false;
        }
        
        /** @var Ticket $model */
        $ticketCategory = $model->ticketCategoria;
        if (is_null($ticketCategory)) {
            return false;
        }
        
        return $ticketCategory->isAdministrative();
    }
}
