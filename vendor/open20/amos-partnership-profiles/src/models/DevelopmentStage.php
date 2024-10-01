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
 * Class DevelopmentStage
 * This is the model class for table "development_stage".
 * @package open20\amos\partnershipprofiles\models
 */
class DevelopmentStage extends \open20\amos\partnershipprofiles\models\base\DevelopmentStage
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
