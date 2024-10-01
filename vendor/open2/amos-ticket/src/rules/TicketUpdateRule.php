<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\rules
 * @category   CategoryName
 */

namespace open2\amos\ticket\rules;

use open20\amos\core\rules\BasicContentRule;
use open2\amos\ticket\models\Ticket;
use raoul2000\workflow\base\SimpleWorkflowBehavior;

/**
 * Class TicketUpdateRule
 * @package open2\amos\ticket\rules
 */
class TicketUpdateRule extends BasicContentRule
{
    public $name = 'TicketUpdateRule';

    /**
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        /** @var Ticket|SimpleWorkflowBehavior $model */
        if (!$model->id) {
            return true;
        }
        $modelWorkflowStatus = $model->getWorkflowStatus();
        if (!empty($modelWorkflowStatus)) {
            return (
                ($modelWorkflowStatus->getId() == Ticket::TICKET_WORKFLOW_STATUS_WAITING) &&
                (($model->created_by == $user) || $model->isReferente($user))
            );
        }
        return false;
    }
}
