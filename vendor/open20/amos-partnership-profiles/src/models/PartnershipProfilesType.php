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
 * Class PartnershipProfilesType
 * This is the model class for table "partnership_profiles_type".
 * @package open20\amos\partnershipprofiles\models
 */
class PartnershipProfilesType extends \open20\amos\partnershipprofiles\models\base\PartnershipProfilesType
{
    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'name'
        ];
    }
}
