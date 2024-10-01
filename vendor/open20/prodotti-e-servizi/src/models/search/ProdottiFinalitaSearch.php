<?php

namespace open20\prodottiservizi\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\prodottiservizi\models\ProdottiFinalita;

/**
 * ProdottiFinalitaSearch represents the model behind the search form about `open20\prodottiservizi\models\ProdottiFinalita`.
 */
class ProdottiFinalitaSearch extends ProdottiFinalita
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
            [['id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = ProdottiFinalita::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->orderBy('name ASC');

        $dataProvider->setSort([
            'attributes' => [
                'template' => [
                    'asc' => ['prodotti_finalita.template' => SORT_ASC],
                    'desc' => ['prodotti_finalita.template' => SORT_DESC],
                ],
                'vendorPath' => [
                    'asc' => ['prodotti_finalita.vendorPath' => SORT_ASC],
                    'desc' => ['prodotti_finalita.vendorPath' => SORT_DESC],
                ],
                'providerList' => [
                    'asc' => ['prodotti_finalita.providerList' => SORT_ASC],
                    'desc' => ['prodotti_finalita.providerList' => SORT_DESC],
                ],
                'actionButtonClass' => [
                    'asc' => ['prodotti_finalita.actionButtonClass' => SORT_ASC],
                    'desc' => ['prodotti_finalita.actionButtonClass' => SORT_DESC],
                ],
                'viewPath' => [
                    'asc' => ['prodotti_finalita.viewPath' => SORT_ASC],
                    'desc' => ['prodotti_finalita.viewPath' => SORT_DESC],
                ],
                'pathPrefix' => [
                    'asc' => ['prodotti_finalita.pathPrefix' => SORT_ASC],
                    'desc' => ['prodotti_finalita.pathPrefix' => SORT_DESC],
                ],
                'savedForm' => [
                    'asc' => ['prodotti_finalita.savedForm' => SORT_ASC],
                    'desc' => ['prodotti_finalita.savedForm' => SORT_DESC],
                ],
                'formLayout' => [
                    'asc' => ['prodotti_finalita.formLayout' => SORT_ASC],
                    'desc' => ['prodotti_finalita.formLayout' => SORT_DESC],
                ],
                'accessFilter' => [
                    'asc' => ['prodotti_finalita.accessFilter' => SORT_ASC],
                    'desc' => ['prodotti_finalita.accessFilter' => SORT_DESC],
                ],
                'generateAccessFilterMigrations' => [
                    'asc' => ['prodotti_finalita.generateAccessFilterMigrations' => SORT_ASC],
                    'desc' => ['prodotti_finalita.generateAccessFilterMigrations' => SORT_DESC],
                ],
                'singularEntities' => [
                    'asc' => ['prodotti_finalita.singularEntities' => SORT_ASC],
                    'desc' => ['prodotti_finalita.singularEntities' => SORT_DESC],
                ],
                'modelMessageCategory' => [
                    'asc' => ['prodotti_finalita.modelMessageCategory' => SORT_ASC],
                    'desc' => ['prodotti_finalita.modelMessageCategory' => SORT_DESC],
                ],
                'controllerClass' => [
                    'asc' => ['prodotti_finalita.controllerClass' => SORT_ASC],
                    'desc' => ['prodotti_finalita.controllerClass' => SORT_DESC],
                ],
                'modelClass' => [
                    'asc' => ['prodotti_finalita.modelClass' => SORT_ASC],
                    'desc' => ['prodotti_finalita.modelClass' => SORT_DESC],
                ],
                'searchModelClass' => [
                    'asc' => ['prodotti_finalita.searchModelClass' => SORT_ASC],
                    'desc' => ['prodotti_finalita.searchModelClass' => SORT_DESC],
                ],
                'baseControllerClass' => [
                    'asc' => ['prodotti_finalita.baseControllerClass' => SORT_ASC],
                    'desc' => ['prodotti_finalita.baseControllerClass' => SORT_DESC],
                ],
                'indexWidgetType' => [
                    'asc' => ['prodotti_finalita.indexWidgetType' => SORT_ASC],
                    'desc' => ['prodotti_finalita.indexWidgetType' => SORT_DESC],
                ],
                'enableI18N' => [
                    'asc' => ['prodotti_finalita.enableI18N' => SORT_ASC],
                    'desc' => ['prodotti_finalita.enableI18N' => SORT_DESC],
                ],
                'enablePjax' => [
                    'asc' => ['prodotti_finalita.enablePjax' => SORT_ASC],
                    'desc' => ['prodotti_finalita.enablePjax' => SORT_DESC],
                ],
                'messageCategory' => [
                    'asc' => ['prodotti_finalita.messageCategory' => SORT_ASC],
                    'desc' => ['prodotti_finalita.messageCategory' => SORT_DESC],
                ],
                'formTabs' => [
                    'asc' => ['prodotti_finalita.formTabs' => SORT_ASC],
                    'desc' => ['prodotti_finalita.formTabs' => SORT_DESC],
                ],
                'tabsFieldList' => [
                    'asc' => ['prodotti_finalita.tabsFieldList' => SORT_ASC],
                    'desc' => ['prodotti_finalita.tabsFieldList' => SORT_DESC],
                ],
                'relFiledsDynamic' => [
                    'asc' => ['prodotti_finalita.relFiledsDynamic' => SORT_ASC],
                    'desc' => ['prodotti_finalita.relFiledsDynamic' => SORT_DESC],
                ],
            ]]);

        if (!($this->load($params) && $this->validate())) {
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

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}