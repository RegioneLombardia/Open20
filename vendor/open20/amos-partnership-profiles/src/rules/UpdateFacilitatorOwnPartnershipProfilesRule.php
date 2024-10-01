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

/**
 * Class UpdateFacilitatorOwnPartnershipProfilesRule
 * @package open20\amos\partnershipprofiles\rules
 */
class UpdateFacilitatorOwnPartnershipProfilesRule extends FacilitatorOwnPartnershipProfilesRule
{
    public $name = 'updateFacilitatorOwnPartnershipProfiles';

    /**
     * @inheritdoc
     */
    public function facilitatorLogic($user, $item, $params, $model)
    {
        return true;
    }
}
