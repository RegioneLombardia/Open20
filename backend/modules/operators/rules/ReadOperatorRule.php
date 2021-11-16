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
 * Class ReadOperatorRule
 * @package backend\modules\campains\rules
 */
class ReadOperatorRule extends BasicContentRule
{
    public $name = 'readOperator';
    
    /**
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {

        return true;
    }
}
