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
use open20\amos\partnershipprofiles\models\PartnershipProfiles;

/**
 * Class ReadAllExprOfIntRule
 * @package open20\amos\partnershipprofiles\rules
 */
class ReadAllExprOfIntRule extends BasicContentRule
{
    public $name = 'ReadAllExprOfInt';

    /**
     * Rule to see the link "see all" Expression of interest
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        /** @var PartnershipProfiles $model */

        if (!is_null($model->partnershipProfileFacilitator) && ($user == $model->partnershipProfileFacilitator->user_id)) {
            // Can see "see all" the facilitator of the PartnershipProfile
            return true;
        } else if ($model->created_by == $user) {
            // Can see "see all" the creator of the PartnershipProfile
            return true;
        } else {
            // Can see "see all" if you are creator of a EOI or if you are FACILITATOR of the creator of EOI
            $exprOfIntList = $model->notDraftExpressionsOfInterest;
            foreach ($exprOfIntList as $exprOfInterest) {
                /** @var ExpressionsOfInterest $exprOfInterest */
                if ($exprOfInterest->created_by == $user) {
                    return true;
                }
                $facilitator = $exprOfInterest->createdUserProfile->facilitatore;
                if (!is_null($facilitator) && ($facilitator->user_id == $user)) {
                    return true;
                }
            }
        }
        return false;
    }
}
