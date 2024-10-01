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
use open20\amos\partnershipprofiles\models\PartnershipProfiles;

/**
 * Class PartnershipProfilesCloseStatusRule
 * @package open20\amos\partnershipprofiles\rules
 */
class PartnershipProfilesCloseStatusRule extends BasicContentRule
{
    public $name = 'partnershipProfilesCloseStatus';

    /**
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        $canClose = false;

        if ($model instanceof PartnershipProfiles) {
            /** @var PartnershipProfiles $model */
            $allowedUserIds = [
                $model->created_by,
            ];
            $facilitator = $model->partnershipProfileFacilitator;
            if (!is_null($facilitator)) {
                $allowedUserIds[] = $facilitator->user_id;
            }
            $notAllowedStates = [
                PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_ARCHIVED,
                PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_FEEDBACKRECEIVED
            ];
            $canClose = (
                in_array($user, $allowedUserIds) &&
                !in_array($model->status, $notAllowedStates)
            );
        }

        return $canClose;
    }
}
