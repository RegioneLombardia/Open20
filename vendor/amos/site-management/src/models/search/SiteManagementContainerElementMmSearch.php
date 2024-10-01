<?php

namespace amos\sitemanagement\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use amos\sitemanagement\models\SiteManagementContainerElementMm;

/**
* SiteManagementContainerElementMmSearch represents the model behind the search form about `amos\sitemanagement\models\SiteManagementContainerElementMm`.
*/
class SiteManagementContainerElementMmSearch extends SiteManagementContainerElementMm
{
    public function rules()
    {
        return [
            [['id', 'container_id', 'element_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
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
        $query = SiteManagementContainerElementMm::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $scope = $this->getScope($params);

        if (!($this->load($params, $scope) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'container_id' => $this->container_id,
            'element_id' => $this->element_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        return $dataProvider;
    }
}