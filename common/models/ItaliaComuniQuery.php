<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

namespace common\models;

/**
 * This is the ActiveQuery class for [[ItaliaComuni]].
 *
 */
class ItaliaComuniQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return ItaliaComuni[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ItaliaComuni|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}