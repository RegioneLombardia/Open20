<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag
 * @category   CategoryName
 */

namespace open20\amos\tag\models\search;

use open20\amos\tag\behaviors\NestedSetsQueryBehavior;

class TagQuery extends \yii\db\ActiveQuery
{
    public function behaviors()
    {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}