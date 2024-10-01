<?php

namespace open20\prodottiservizi\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\prodottiservizi\models\ProdottiSoluzioni;

/**
 * ProdottiSoluzioniSearch represents the model behind the search form about `open20\prodottiservizi\models\ProdottiSoluzioni`.
 */
class ProdottiSoluzioniSearch extends ProdottiSoluzioni
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
            [['name', 'icon', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = ProdottiSoluzioni::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->orderBy('name ASC');

        $dataProvider->setSort([
            'attributes' => [
                'template' => [
                    'asc' => ['prodotti_soluzioni.template' => SORT_ASC],
                    'desc' => ['prodotti_soluzioni.template' => SORT_DESC],
                ],
                'vendorPath' => [
                    'asc' => ['prodotti_soluzioni.vendorPath' => SORT_ASC],
                    'desc' => ['prodotti_soluzioni.vendorPath' => SORT_DESC],
                ],
                'providerList' => [
                    'asc' => ['prodotti_soluzioni.providerList' => SORT_ASC],
                    'desc' => ['prodotti_soluzioni.providerList' => SORT_DESC],
                ],
                'actionButtonClass' => [
                    'asc' => ['prodotti_soluzioni.actionButtonClass' => SORT_ASC],
                    'desc' => ['prodotti_soluzioni.actionButtonClass' => SORT_DESC],
                ],
                'viewPath' => [
                    'asc' => ['prodotti_soluzioni.viewPath' => SORT_ASC],
                    'desc' => ['prodotti_soluzioni.viewPath' => SORT_DESC],
                ],
                'pathPrefix' => [
                    'asc' => ['prodotti_soluzioni.pathPrefix' => SORT_ASC],
                    'desc' => ['prodotti_soluzioni.pathPrefix' => SORT_DESC],
                ],
                'savedForm' => [
                    'asc' => ['prodotti_soluzioni.savedForm' => SORT_ASC],
                    'desc' => ['prodotti_soluzioni.savedForm' => SORT_DESC],
                ],
                'formLayout' => [
                    'asc' => ['prodotti_soluzioni.formLayout' => SORT_ASC],
                    'desc' => ['prodotti_soluzioni.formLayout' => SORT_DESC],
                ],
                'accessFilter' => [
                    'asc' => ['prodotti_soluzioni.accessFilter' => SORT_ASC],
                    'desc' => ['prodotti_soluzioni.accessFilter' => SORT_DESC],
                ],
                'generateAccessFilterMigrations' => [
                    'asc' => ['prodotti_soluzioni.generateAccessFilterMigrations' => SORT_ASC],
                    'desc' => ['prodotti_soluzioni.generateAccessFilterMigrations' => SORT_DESC],
                ],
                'singularEntities' => [
                    'asc' => ['prodotti_soluzioni.singularEntities' => SORT_ASC],
                    'desc' => ['prodotti_soluzioni.singularEntities' => SORT_DESC],
                ],
                'modelMessageCategory' => [
                    'asc' => ['prodotti_soluzioni.modelMessageCategory' => SORT_ASC],
                    'desc' => ['prodotti_soluzioni.modelMessageCategory' => SORT_DESC],
                ],
                'controllerClass' => [
                    'asc' => ['prodotti_soluzioni.controllerClass' => SORT_ASC],
                    'desc' => ['prodotti_soluzioni.controllerClass' => SORT_DESC],
                ],
                'modelClass' => [
                    'asc' => ['prodotti_soluzioni.modelClass' => SORT_ASC],
                    'desc' => ['prodotti_soluzioni.modelClass' => SORT_DESC],
                ],
                'searchModelClass' => [
                    'asc' => ['prodotti_soluzioni.searchModelClass' => SORT_ASC],
                    'desc' => ['prodotti_soluzioni.searchModelClass' => SORT_DESC],
                ],
                'baseControllerClass' => [
                    'asc' => ['prodotti_soluzioni.baseControllerClass' => SORT_ASC],
                    'desc' => ['prodotti_soluzioni.baseControllerClass' => SORT_DESC],
                ],
                'indexWidgetType' => [
                    'asc' => ['prodotti_soluzioni.indexWidgetType' => SORT_ASC],
                    'desc' => ['prodotti_soluzioni.indexWidgetType' => SORT_DESC],
                ],
                'enableI18N' => [
                    'asc' => ['prodotti_soluzioni.enableI18N' => SORT_ASC],
                    'desc' => ['prodotti_soluzioni.enableI18N' => SORT_DESC],
                ],
                'enablePjax' => [
                    'asc' => ['prodotti_soluzioni.enablePjax' => SORT_ASC],
                    'desc' => ['prodotti_soluzioni.enablePjax' => SORT_DESC],
                ],
                'messageCategory' => [
                    'asc' => ['prodotti_soluzioni.messageCategory' => SORT_ASC],
                    'desc' => ['prodotti_soluzioni.messageCategory' => SORT_DESC],
                ],
                'formTabs' => [
                    'asc' => ['prodotti_soluzioni.formTabs' => SORT_ASC],
                    'desc' => ['prodotti_soluzioni.formTabs' => SORT_DESC],
                ],
                'tabsFieldList' => [
                    'asc' => ['prodotti_soluzioni.tabsFieldList' => SORT_ASC],
                    'desc' => ['prodotti_soluzioni.tabsFieldList' => SORT_DESC],
                ],
                'relFiledsDynamic' => [
                    'asc' => ['prodotti_soluzioni.relFiledsDynamic' => SORT_ASC],
                    'desc' => ['prodotti_soluzioni.relFiledsDynamic' => SORT_DESC],
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

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'icon', $this->icon]);

        return $dataProvider;
    }
}