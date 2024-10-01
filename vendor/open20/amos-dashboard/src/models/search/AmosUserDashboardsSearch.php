<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\dashboard
 * @category   CategoryName
 */

namespace open20\amos\dashboard\models\search;

use open20\amos\dashboard\models\AmosUserDashboards;
use yii\db\ActiveQuery;

/**
 * AmosUserDashboardsSearch represents the model behind the search form about `open20\amos\dashboard\models\AmosUserDashboards`.
 */
class AmosUserDashboardsSearch extends AmosUserDashboards
{
    /**@return ActiveQuery */
    public function current($params)
    {
        $query = AmosUserDashboards::find();

        if (!($this->load($params) && $this->validate())) {
            return $query->andFilterWhere($params);
        }
    }

    public function rules()
    {
        return [
            [['id', 'user_id', 'slide', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['module', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

}