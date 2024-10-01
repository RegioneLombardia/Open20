<?php

namespace amos\sitemanagement\models\search;

use amos\sitemanagement\models\SiteManagementSliderElem;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
* SiteManagementSliderElemSearch represents the model behind the search form about `amos\sitemanagement\models\SiteManagementSliderElem`.
*/
class SiteManagementSliderElemSearch extends \amos\sitemanagement\models\SiteManagementSliderElem
{
    public function rules()
    {
        return [
            [['id', 'slider_id', 'type', 'order', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['title', 'description', 'url_video', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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
        $query = SiteManagementSliderElem::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $scope = $this->getScope($params);

        if (!($this->load($params, $scope) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'slider_id' => $this->slider_id,
            'type' => $this->type,
            'order' => $this->order,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'url_video', $this->url_video]);

        return $dataProvider;
    }
}