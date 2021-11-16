<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    backend\modules\campains\rules
 * @category   CategoryName
 */

namespace backend\modules\operators\rules;

use open20\amos\core\rules\BasicContentRule;

/**
 * Class CreateOperatorRule
 * @package backend\modules\campains\rules
 */
class CreateOperatorRule extends BasicContentRule
{
    public $name = 'createOperator';
    
    /**
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {

        return true;
    }
}
