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

/**
 * Class TicketCategoriaDeleteRule
 * @package open2\amos\ticket\rules
 */
class TicketCategoriaDeleteRule extends BasicContentRule
{
    public $name = 'TicketCategoriaDeleteRule';

    /**
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        $ticketAssociati = $model->ticket;
        if (empty($ticketAssociati)) {
            return true;
        } else {
            return false;
        }
    }
}
