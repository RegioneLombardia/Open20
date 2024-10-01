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
 * Class DeleteOwnPartnershipProfilesRule
 * @package open20\amos\partnershipprofiles\rules
 */
class DeleteOwnPartnershipProfilesRule extends OwnPartnershipProfileRule
{
    public $name = 'deleteOwnPartnershipProfiles';

    /**
     * @inheritdoc
     */
    public function partnershipProfileLogic($user, $item, $params, $model)
    {
        return (!count($model->expressionsOfInterest)); // Return true if the partnership profile doesn't have expressions of interest.
    }
}
