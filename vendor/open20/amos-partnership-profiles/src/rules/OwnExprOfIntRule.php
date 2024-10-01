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
 * Class OwnExprOfIntRule
 * @package open20\amos\partnershipprofiles\rules
 */
abstract class OwnExprOfIntRule extends BasicContentRule
{
    /**
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        /** @var ExpressionsOfInterest $model */

        $allowedPartnershipProfileStates = [
            PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_VALIDATED,
            PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_FEEDBACKRECEIVED
        ];

        return (
            ($model->created_by == $user) &&
            ($model->status == ExpressionsOfInterest::EXPRESSIONS_OF_INTEREST_WORKFLOW_STATUS_DRAFT) &&
            in_array($model->partnershipProfile->status, $allowedPartnershipProfileStates)
        );
    }
}
