<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\events
 * @category   CategoryName
 */

namespace open20\amos\partnershipprofiles\events;

use open20\amos\partnershipprofiles\models\ExpressionsOfInterest;
use open20\amos\partnershipprofiles\models\PartnershipProfiles;
use yii\base\Event;
use yii\base\BaseObject;

/**
 * Class PartnershipProfilesWorkflowEvent
 * @package open20\amos\partnershipprofiles\events
 */
class PartnershipProfilesWorkflowEvent extends BaseObject
{
    /**
     * @param \yii\base\Event $yiiEvent
     * @return bool
     */
    public function updatePartnershipProfileStatus(Event $yiiEvent)
    {
        /** @var ExpressionsOfInterest $expressionOfInterest */
        $expressionOfInterest = $yiiEvent->data;
        $partnershipProfile = $expressionOfInterest->partnershipProfile;
        $ok = true;
        if ($partnershipProfile->status == PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_VALIDATED) {
            $partnershipProfile->sendToStatus(PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_FEEDBACKRECEIVED);
            $ok = $partnershipProfile->save(false);
        }
        return $ok;
    }
}
