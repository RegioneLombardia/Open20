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
 * Class TicketReadRule
 * @package open2\amos\ticket\rules
 */
class TicketReadRule extends BasicContentRule
{
    public $name = 'TicketReadRule';

    /**
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        return ($model->created_by == $user) || $model->isReferente($user) || $model->isAncestorVisible();
    }
}
