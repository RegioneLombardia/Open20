<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    backend\modules\campains\rules
 * @category   CategoryName
 */

namespace backend\modules\campains\rules;

use open20\amos\core\rules\BasicContentRule;

/**
 * Class CreateCampainRule
 * @package backend\modules\campains\rules
 */
class CreateCampainRule extends BasicContentRule
{
    public $name = 'createCampain';
    
    /**
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        return true;
    }
}
