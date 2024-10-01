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
 * Class WorkLanguage
 * This is the model class for table "work_language".
 * @package open20\amos\partnershipprofiles\models
 */
class WorkLanguage extends \open20\amos\partnershipprofiles\models\base\WorkLanguage
{
    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'work_language'
        ];
    }
}
