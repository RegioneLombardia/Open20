<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

namespace open20\amos\cwh\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "cwh_auth_assignment".
 */
class CwhAuthAssignmentSearch extends \open20\amos\cwh\models\base\CwhAuthAssignment
{
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['item_name', 'cwh_nodi_id'],'string'],
            [['item_name', 'cwh_nodi_id'],'safe'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = CwhAuthAssignmentSearch::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'cwh_nodi_id' => $this->cwh_nodi_id,
        ]);

        $query->andFilterWhere(['like', 'item_name', $this->item_name]);



        return $dataProvider;
    }

}
