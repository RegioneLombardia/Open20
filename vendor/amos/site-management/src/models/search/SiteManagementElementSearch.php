<?php

namespace amos\sitemanagement\models\search;

use amos\sitemanagement\models\SiteManagementContainer;
use amos\sitemanagement\models\SiteManagementContainerElementMm;
use amos\sitemanagement\utility\SiteManagementUtility;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use amos\sitemanagement\models\SiteManagementElement;

/**
* SiteManagementElementSearch represents the model behind the search form about `amos\sitemanagement\models\SiteManagementElement`.
*/
class SiteManagementElementSearch extends SiteManagementElement
{
    public function rules()
    {
        return [
            [['id', 'template_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['title', 'description', 'elem_order', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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
        $query = SiteManagementElement::find();

        // if you don't set  in the platform the permission you can't filter for permission
        if (!empty($permissions)) {
            if (!\Yii::$app->user->can('SITE_MANAGEMENT_ADMINISTRATOR')) {
                $canModify = SiteManagementUtility::getPermissionUserCan($permissions);
                $a = SiteManagementContainerElementMm::find()
                                ->joinWith('container')
                                ->andWhere([
                                    'OR',
                                    ['site_management_container.permission' => $canModify],
                                    ['site_management_container.permission' => null],
                                ])->all();

                $elementsIds = [];

                foreach ($a as $elemMm) {
                    $elementsIds [] = $elemMm->element_id;
                }

                $query->andWhere(['site_management_element.id' => $elementsIds]);
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
            'template_id' => $this->template_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'elem_order', $this->elem_order]);
        return $dataProvider;
    }
}