<?php

namespace open20\amos\admin\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\amos\admin\models\TokenGroup;

/**
* TokenGroupSearch represents the model behind the search form about `open20\amos\admin\models\TokenGroup`.
*/
class TokenGroupSearch extends TokenGroup
{
    public function rules()
    {
        return [
            [['id', 'target_id', 'consumable', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name', 'Description', 'url_redirect', 'target_class', 'expire_date', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function getScope($params)
    {
        $scope = $this->formName();
        if( !isset( $params[$scope]) ){
            $scope = '';
        }
        return $scope;
    }

    public function search($params)
    {
        $query = TokenGroup::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $scope = $this->getScope($params);

        if (!($this->load($params, $scope) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'target_id' => $this->target_id,
            'consumable' => $this->consumable,
            'expire_date' => $this->expire_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'Description', $this->Description])
            ->andFilterWhere(['like', 'url_redirect', $this->url_redirect])
            ->andFilterWhere(['like', 'target_class', $this->target_class]);

        return $dataProvider;
    }
}