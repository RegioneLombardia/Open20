<?php

namespace amos\sitemanagement\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use amos\sitemanagement\models\SiteManagementTemplate;

/**
* SiteManagementTemplateSearch represents the model behind the search form about `amos\sitemanagement\models\SiteManagementTemplate`.
*/
class SiteManagementTemplateSearch extends SiteManagementTemplate
{
    public function rules()
    {
        return [
            [['id', 'name', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['description', 'view_path', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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
        $query = SiteManagementTemplate::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $scope = $this->getScope($params);

        if (!($this->load($params, $scope) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'view_path', $this->view_path]);

        return $dataProvider;
    }
}