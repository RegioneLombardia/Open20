<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag
 * @category   CategoryName
 */

namespace open20\amos\tag\models\search;

use open20\amos\tag\behaviors\NestedSetsQueryBehavior;
use yii\db\ActiveQuery;

/**
 * 
 */
class TagQuery extends ActiveQuery
{

    public function behaviors()
    {
        return [
            NestedSetsQueryBehavior::class,
        ];
    }

}