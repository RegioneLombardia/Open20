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
 * Class UpdateOwnPartnershipProfilesRule
 * @package open20\amos\partnershipprofiles\rules
 */
class UpdateOwnPartnershipProfilesRule extends OwnPartnershipProfileRule
{
    public $name = 'updateOwnPartnershipProfiles';

    /**
     * @inheritdoc
     */
    public function partnershipProfileLogic($user, $item, $params, $model)
    {
        return true;
    }
}
