<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\rules
 * @category   CategoryName
 */

namespace open20\amos\partnershipprofiles\rules;

use open20\amos\core\rules\BasicContentRule;
use open20\amos\partnershipprofiles\models\ExpressionsOfInterest;

/**
 * Class ChangePartPropExprsOfIntStatusRule
 * @package open20\amos\partnershipprofiles\rules
 */
class ChangePartPropExprsOfIntStatusRule extends BasicContentRule
{
    public $name = 'changePartPropExprsOfIntStatus';

    /**
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        /** @var ExpressionsOfInterest $model */
        return ($model->partnershipProfile->created_by == $user);
    }
}
