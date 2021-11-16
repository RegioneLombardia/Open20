<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    backend\modules\campains\rules
 * @category   CategoryName
 */

namespace backend\modules\registeredusers\rules;

use open20\amos\core\rules\BasicContentRule;

/**
 * Class UpdateOperatorRule
 * @package backend\modules\campains\rules
 */
class UpdateRegisteredUserRule extends BasicContentRule
{
    public $name = 'updateRegisteredUser';
    
    /**
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        return true;
    }
}
