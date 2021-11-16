<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\models\search
 * @category   CategoryName
 */

namespace open20\amos\admin\models\search;

use open20\amos\admin\models\UserProfileArea;

/**
 * Class UserProfileAreaSearch
 * @package open20\amos\admin\models\search
 */
class UserProfileAreaSearch extends UserProfileArea
{
    /**
     * This search method retrieve all elements in the table. You can specify if you want
     * only the enabled elements by set to true the "onlyEnabled" param. You can set the
     * order by the "order" param equal to che orderBy method of an ActiveQuery.
     * The query find the element ordered by default by the "order" field ASC.
     * @param bool $onlyEnabled Default to true
     * @param array $order Default to ['order' => SORT_ASC]
     * @return UserProfileArea[]
     */
    public static function searchAll($onlyEnabled = true, $order = null)
    {
        $defaultOrder = ['order' => SORT_ASC];
        $queryOrder = ((!is_null($order) && is_array($order) && isset($order['order']) && is_numeric($order['order'])) ? $order : $defaultOrder);
        /** @var \yii\db\ActiveQuery $query */
        $query = UserProfileArea::find();
        $query->orderBy($queryOrder);
        if ($onlyEnabled) {
            $query->andWhere(['enabled' => 1]);
        }
        return $query->asArray()->all();
    }
}
