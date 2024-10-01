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
 * Class PartnershipProfilesCountriesMm
 * This is the model class for table "partnership_profiles_countries_mm".
 * @package open20\amos\partnershipprofiles\models
 */
class PartnershipProfilesCountriesMm extends \open20\amos\partnershipprofiles\models\base\PartnershipProfilesCountriesMm
{
    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'partnership_profile_id',
            'country_id'
        ];
    }
}
