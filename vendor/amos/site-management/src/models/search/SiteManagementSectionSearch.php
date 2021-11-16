<?php

namespace amos\sitemanagement\models\search;

use amos\sitemanagement\models\SiteManagementSection;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\amos\core\interfaces\CmsModelInterface;
use open20\amos\core\record\CmsField;

/**
 * SiteManagementSectionSearch represents the model behind the search form about `amos\sitemanagement\models\SiteManagementSection`.
 */
class SiteManagementSectionSearch extends \amos\sitemanagement\models\SiteManagementSection implements CmsModelInterface {

    public function rules() {
        return [
            [['id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name', 'description', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function getScope($params) {
        $scope = $this->formName();
        if (!isset($params[$scope])) {
            $scope = '';
        }
        return $scope;
    }

    public function search($params) {
        $query = SiteManagementSection::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $scope = $this->getScope($params);

        if (!($this->load($params, $scope) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }

    public function searchAvailableSections() {
        
    }

    /**
     *
     * @param type $params
     * @param type $limit
     * @return ActiveDataProvider
     */
    public function cmsSearch($params, $limit = null) {
        $dataProvider = $this->search($params, $limit = null);
        if (!empty($params["withPagination"])) {
            $dataProvider->setPagination(['pageSize' => $limit]);
            $dataProvider->query->limit(null);
        } else {
            $dataProvider->query->limit($limit);
        }

        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                $dataProvider->query->andWhere(eval("return " . $command . ";"));
            }
        }
        return $dataProvider;
    }

    /**
     *
     * @return array
     */
    public function cmsViewFields() {
        $viewFields = [];

        array_push($viewFields, new CmsField("name", "TEXT", 'amossitemanagement', $this->attributeLabels()["name"]));

        return $viewFields;
    }

    /**
     *
     * @return array
     */
    public function cmsSearchFields() {
        $searchFields = [];

        array_push($searchFields, new CmsField("name", "TEXT"));

        return $searchFields;
    }

    /**
     *
     * @param type $id
     * @return boolean
     */
    public function cmsIsVisible($id) {
        return true;
    }

}
