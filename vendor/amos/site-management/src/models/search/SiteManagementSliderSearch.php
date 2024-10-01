<?php

namespace amos\sitemanagement\models\search;

use amos\sitemanagement\utility\SiteManagementUtility;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use amos\sitemanagement\models\SiteManagementSlider;

/**
* SiteManagementSliderSearch represents the model behind the search form about `amos\sitemanagement\models\SiteManagementSlider`.
*/
class SiteManagementSliderSearch extends SiteManagementSlider
{
    public function rules()
    {
        return [
            [['id', 'section_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['title', 'description', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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
        $permissions = SiteManagementUtility::getEnabledPermissionsForUpdate();

        $query = SiteManagementSlider::find();

        // if you don't set  in the platform the permission you can't filter for permission
        if(!empty($permissions)){
            if(!\Yii::$app->user->can('SITE_MANAGEMENT_ADMINISTRATOR')) {
                $canModify = SiteManagementUtility::getPermissionUserCan($permissions);
                $query->andWhere([
                    'OR',
                    ['permission' => $canModify],
                    ['permission' => null],
                ]);
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $scope = $this->getScope($params);

        if (!($this->load($params, $scope) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'section_id' => $this->section_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }

}