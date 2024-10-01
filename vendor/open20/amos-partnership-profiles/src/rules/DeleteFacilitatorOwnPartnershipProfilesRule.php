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
 * Class DeleteFacilitatorOwnPartnershipProfilesRule
 * @package open20\amos\partnershipprofiles\rules
 */
class DeleteFacilitatorOwnPartnershipProfilesRule extends FacilitatorOwnPartnershipProfilesRule
{
    public $name = 'deleteFacilitatorOwnPartnershipProfiles';

    /**
     * @inheritdoc
     */
    public function facilitatorLogic($user, $item, $params, $model)
    {
        return (!count($model->expressionsOfInterest)); // Return true if the partnership profile doesn't have expressions of interest.
    }
}
