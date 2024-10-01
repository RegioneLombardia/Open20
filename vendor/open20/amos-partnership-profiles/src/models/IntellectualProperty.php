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
 * Class IntellectualProperty
 * This is the model class for table "intellectual_property".
 * @package open20\amos\partnershipprofiles\models
 */
class IntellectualProperty extends \open20\amos\partnershipprofiles\models\base\IntellectualProperty
{
    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'value'
        ];
    }
}
