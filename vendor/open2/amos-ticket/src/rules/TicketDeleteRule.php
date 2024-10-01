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
 * Class TicketDeleteRule
 * @package open2\amos\ticket\rules
 */
class TicketDeleteRule extends BasicContentRule
{
    public $name = 'TicketDeleteRule';

    /**
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        //$referenteFlag = $model->isReferente($user);
        return false;
    }
}
