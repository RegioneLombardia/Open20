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
 * Class PartnershipProfilesDraftStatusRule
 * @package open20\amos\partnershipprofiles\rules
 */
class PartnershipProfilesDraftStatusRule extends BasicContentRule
{
    public $name = 'partnershipProfilesDraftStatus';

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
            $canClose = in_array($user, $allowedUserIds);
        }

        return $canClose;
    }
}
