<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\models
 * @category   CategoryName
 */

namespace open20\amos\partnershipprofiles\models;

/**
 * Class PartnershipProfilesTypesMm
 * This is the model class for table "partnership_profiles_types_mm".
 * @package open20\amos\partnershipprofiles\models
 */
class PartnershipProfilesTypesMm extends \open20\amos\partnershipprofiles\models\base\PartnershipProfilesTypesMm
{
    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'partnership_profile_id',
            'partnership_profiles_type_id'
        ];
    }
}
