<?php

namespace open20\agid\dataset\models\search;

use open20\amos\core\interfaces\CmsModelInterface;
use open20\amos\core\record\CmsField;
use open20\agid\dataset\models\AgidDataset;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\amos\tag\models\EntitysTagsMm;

/**
 * AgidDatasetSearch represents the model behind the search form about `app\models\AgidDataset`.
 */
class AgidDatasetSearch extends AgidDataset implements CmsModelInterface
{

//private $container;

    public function __construct(array $config = [])
    {
        $this->isSearch = true;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['id', 'agid_dataset_content_type_id', 'agid_dataset_type_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['title', 'description', 'status', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            ['AgidDatasetContentType', 'safe'],
            ['AgidDatasetType', 'safe'],
        ];
    }

    public function scenarios()
    {
		// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params, $queryType = NULL, $limit = NULL, $onlyDrafts = false, $pageSize = NULL)

    {
        $query = AgidDataset::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith('agidDatasetContentType');
        $query->joinWith('agidDatasetType');
        $query->distinct()->leftJoin(EntitysTagsMm::tableName(), EntitysTagsMm::tableName() . ".classname = '".  str_replace('\\','\\\\',AgidDataset::className()) . "' and ".EntitysTagsMm::tableName(). ".record_id = ". AgidDataset::tableName() . ".id and " . EntitysTagsMm::tableName(). ".deleted_at is NULL");  


        $dataProvider->setSort([
            'attributes' => [
                'template' => [
                    'asc' => ['agid_dataset.template' => SORT_ASC],
                    'desc' => ['agid_dataset.template' => SORT_DESC],
                ],
                'vendorPath' => [
                    'asc' => ['agid_dataset.vendorPath' => SORT_ASC],
                    'desc' => ['agid_dataset.vendorPath' => SORT_DESC],
                ],
                'providerList' => [
                    'asc' => ['agid_dataset.providerList' => SORT_ASC],
                    'desc' => ['agid_dataset.providerList' => SORT_DESC],
                ],
                'actionButtonClass' => [
                    'asc' => ['agid_dataset.actionButtonClass' => SORT_ASC],
                    'desc' => ['agid_dataset.actionButtonClass' => SORT_DESC],
                ],
                'viewPath' => [
                    'asc' => ['agid_dataset.viewPath' => SORT_ASC],
                    'desc' => ['agid_dataset.viewPath' => SORT_DESC],
                ],
                'pathPrefix' => [
                    'asc' => ['agid_dataset.pathPrefix' => SORT_ASC],
                    'desc' => ['agid_dataset.pathPrefix' => SORT_DESC],
                ],
                'savedForm' => [
                    'asc' => ['agid_dataset.savedForm' => SORT_ASC],
                    'desc' => ['agid_dataset.savedForm' => SORT_DESC],
                ],
                'formLayout' => [
                    'asc' => ['agid_dataset.formLayout' => SORT_ASC],
                    'desc' => ['agid_dataset.formLayout' => SORT_DESC],
                ],
                'accessFilter' => [
                    'asc' => ['agid_dataset.accessFilter' => SORT_ASC],
                    'desc' => ['agid_dataset.accessFilter' => SORT_DESC],
                ],
                'generateAccessFilterMigrations' => [
                    'asc' => ['agid_dataset.generateAccessFilterMigrations' => SORT_ASC],
                    'desc' => ['agid_dataset.generateAccessFilterMigrations' => SORT_DESC],
                ],
                'singularEntities' => [
                    'asc' => ['agid_dataset.singularEntities' => SORT_ASC],
                    'desc' => ['agid_dataset.singularEntities' => SORT_DESC],
                ],
                'modelMessageCategory' => [
                    'asc' => ['agid_dataset.modelMessageCategory' => SORT_ASC],
                    'desc' => ['agid_dataset.modelMessageCategory' => SORT_DESC],
                ],
                'controllerClass' => [
                    'asc' => ['agid_dataset.controllerClass' => SORT_ASC],
                    'desc' => ['agid_dataset.controllerClass' => SORT_DESC],
                ],
                'modelClass' => [
                    'asc' => ['agid_dataset.modelClass' => SORT_ASC],
                    'desc' => ['agid_dataset.modelClass' => SORT_DESC],
                ],
                'searchModelClass' => [
                    'asc' => ['agid_dataset.searchModelClass' => SORT_ASC],
                    'desc' => ['agid_dataset.searchModelClass' => SORT_DESC],
                ],
                'baseControllerClass' => [
                    'asc' => ['agid_dataset.baseControllerClass' => SORT_ASC],
                    'desc' => ['agid_dataset.baseControllerClass' => SORT_DESC],
                ],
                'indexWidgetType' => [
                    'asc' => ['agid_dataset.indexWidgetType' => SORT_ASC],
                    'desc' => ['agid_dataset.indexWidgetType' => SORT_DESC],
                ],
                'enableI18N' => [
                    'asc' => ['agid_dataset.enableI18N' => SORT_ASC],
                    'desc' => ['agid_dataset.enableI18N' => SORT_DESC],
                ],
                'enablePjax' => [
                    'asc' => ['agid_dataset.enablePjax' => SORT_ASC],
                    'desc' => ['agid_dataset.enablePjax' => SORT_DESC],
                ],
                'messageCategory' => [
                    'asc' => ['agid_dataset.messageCategory' => SORT_ASC],
                    'desc' => ['agid_dataset.messageCategory' => SORT_DESC],
                ],
                'formTabs' => [
                    'asc' => ['agid_dataset.formTabs' => SORT_ASC],
                    'desc' => ['agid_dataset.formTabs' => SORT_DESC],
                ],
                'tabsFieldList' => [
                    'asc' => ['agid_dataset.tabsFieldList' => SORT_ASC],
                    'desc' => ['agid_dataset.tabsFieldList' => SORT_DESC],
                ],
                'relFiledsDynamic' => [
                    'asc' => ['agid_dataset.relFiledsDynamic' => SORT_ASC],
                    'desc' => ['agid_dataset.relFiledsDynamic' => SORT_DESC],
                ],
            ]]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'agid_dataset_content_type_id' => $this->agid_dataset_content_type_id,
            'agid_dataset_type_id' => $this->agid_dataset_type_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }

    public function cmsIsVisible($id) 
    {
        $retValue = true;
        return $retValue;
    }

    public function cmsSearch($params, $limit)
    {
        $params = array_merge($params, Yii::$app->request->get());
        $this->load($params);
        $dataProvider  = $this->search($params);
        $query = $dataProvider->query;
        if ($params["withPagination"]) {
            $dataProvider->setPagination(['pageSize' => $limit]);
            $query->limit(null);
        } else {
            $query->limit($limit);
        }
        $query->andWhere([AgidDataset::tableName().'.status' => AgidDataset::AGID_DATASET_WORKFLOW_STATUS_VALIDATED,]);
        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                $query->andWhere(eval("return ".$command.";"));
            }
        }
        return $dataProvider;
    }

    public function cmsSearchFields() 
    {
        $searchFields = [];

        array_push($searchFields, new CmsField("title", "TEXT"));
        array_push($searchFields, new CmsField("description", "TEXT"));

        return $searchFields;
    }

    public function cmsViewFields() 
    {
        return [
            new CmsField('title', 'TEXT', 'amosdataset', $this->attributeLabels()['title']),
            new CmsField('description', 'TEXT', 'amosdataset', $this->attributeLabels()['description']),
        ];
    }

}
