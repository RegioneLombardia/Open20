<?php

namespace open20\prodottiservizi\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\prodottiservizi\models\ProdottiServizi;

/**
 * ProdottiServiziSearch represents the model behind the search form about `open20\prodottiservizi\models\ProdottiServizi`.
 */
class ProdottiServiziSearch extends ProdottiServizi
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
            [['id', 'id_product_root', 'scope', 'status', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['title', 'description', 'financial_description', 'facilitation_features', 'projects_features', 'expenses_eligible', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['solution','prodotti_servizi_destinatari_mm','prodotti_servizi_finalita_mm'], 'each', 'rule' => ['integer']],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params, $queryLogic='or')
    {
        $query = ProdottiServizi::find();
        $query->joinWith('destinatariMm');
        $query->joinWith('finalitaMm');
        $query->andWhere(['id_product_root' => null]);
        $query->distinct('prodotti_servizi.id');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'template' => [
                    'asc' => ['prodotti_servizi.template' => SORT_ASC],
                    'desc' => ['prodotti_servizi.template' => SORT_DESC],
                ],
                'vendorPath' => [
                    'asc' => ['prodotti_servizi.vendorPath' => SORT_ASC],
                    'desc' => ['prodotti_servizi.vendorPath' => SORT_DESC],
                ],
                'providerList' => [
                    'asc' => ['prodotti_servizi.providerList' => SORT_ASC],
                    'desc' => ['prodotti_servizi.providerList' => SORT_DESC],
                ],
                'actionButtonClass' => [
                    'asc' => ['prodotti_servizi.actionButtonClass' => SORT_ASC],
                    'desc' => ['prodotti_servizi.actionButtonClass' => SORT_DESC],
                ],
                'viewPath' => [
                    'asc' => ['prodotti_servizi.viewPath' => SORT_ASC],
                    'desc' => ['prodotti_servizi.viewPath' => SORT_DESC],
                ],
                'pathPrefix' => [
                    'asc' => ['prodotti_servizi.pathPrefix' => SORT_ASC],
                    'desc' => ['prodotti_servizi.pathPrefix' => SORT_DESC],
                ],
                'savedForm' => [
                    'asc' => ['prodotti_servizi.savedForm' => SORT_ASC],
                    'desc' => ['prodotti_servizi.savedForm' => SORT_DESC],
                ],
                'formLayout' => [
                    'asc' => ['prodotti_servizi.formLayout' => SORT_ASC],
                    'desc' => ['prodotti_servizi.formLayout' => SORT_DESC],
                ],
                'accessFilter' => [
                    'asc' => ['prodotti_servizi.accessFilter' => SORT_ASC],
                    'desc' => ['prodotti_servizi.accessFilter' => SORT_DESC],
                ],
                'generateAccessFilterMigrations' => [
                    'asc' => ['prodotti_servizi.generateAccessFilterMigrations' => SORT_ASC],
                    'desc' => ['prodotti_servizi.generateAccessFilterMigrations' => SORT_DESC],
                ],
                'singularEntities' => [
                    'asc' => ['prodotti_servizi.singularEntities' => SORT_ASC],
                    'desc' => ['prodotti_servizi.singularEntities' => SORT_DESC],
                ],
                'modelMessageCategory' => [
                    'asc' => ['prodotti_servizi.modelMessageCategory' => SORT_ASC],
                    'desc' => ['prodotti_servizi.modelMessageCategory' => SORT_DESC],
                ],
                'controllerClass' => [
                    'asc' => ['prodotti_servizi.controllerClass' => SORT_ASC],
                    'desc' => ['prodotti_servizi.controllerClass' => SORT_DESC],
                ],
                'modelClass' => [
                    'asc' => ['prodotti_servizi.modelClass' => SORT_ASC],
                    'desc' => ['prodotti_servizi.modelClass' => SORT_DESC],
                ],
                'searchModelClass' => [
                    'asc' => ['prodotti_servizi.searchModelClass' => SORT_ASC],
                    'desc' => ['prodotti_servizi.searchModelClass' => SORT_DESC],
                ],
                'baseControllerClass' => [
                    'asc' => ['prodotti_servizi.baseControllerClass' => SORT_ASC],
                    'desc' => ['prodotti_servizi.baseControllerClass' => SORT_DESC],
                ],
                'indexWidgetType' => [
                    'asc' => ['prodotti_servizi.indexWidgetType' => SORT_ASC],
                    'desc' => ['prodotti_servizi.indexWidgetType' => SORT_DESC],
                ],
                'enableI18N' => [
                    'asc' => ['prodotti_servizi.enableI18N' => SORT_ASC],
                    'desc' => ['prodotti_servizi.enableI18N' => SORT_DESC],
                ],
                'enablePjax' => [
                    'asc' => ['prodotti_servizi.enablePjax' => SORT_ASC],
                    'desc' => ['prodotti_servizi.enablePjax' => SORT_DESC],
                ],
                'messageCategory' => [
                    'asc' => ['prodotti_servizi.messageCategory' => SORT_ASC],
                    'desc' => ['prodotti_servizi.messageCategory' => SORT_DESC],
                ],
                'formTabs' => [
                    'asc' => ['prodotti_servizi.formTabs' => SORT_ASC],
                    'desc' => ['prodotti_servizi.formTabs' => SORT_DESC],
                ],
                'tabsFieldList' => [
                    'asc' => ['prodotti_servizi.tabsFieldList' => SORT_ASC],
                    'desc' => ['prodotti_servizi.tabsFieldList' => SORT_DESC],
                ],
                'relFiledsDynamic' => [
                    'asc' => ['prodotti_servizi.relFiledsDynamic' => SORT_ASC],
                    'desc' => ['prodotti_servizi.relFiledsDynamic' => SORT_DESC],
                ],
            ]]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'scope' => $this->scope,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);
        $query->andFilterWhere(
            ['in', 'solution', $this->solution]
        );

         $query->andFilterWhere([
             'in', 'destinatario_id', $this->prodotti_servizi_destinatari_mm
         ]);

        $query->andFilterWhere([
            'in', 'finalita_id', $this->prodotti_servizi_finalita_mm
        ]);
        if($queryLogic == 'and'){
            $having = '';
            if(count($this->prodotti_servizi_destinatari_mm) > 0){
                $having .= 'COUNT(DISTINCT destinatario_id) = ' . count($this->prodotti_servizi_destinatari_mm);
            }
            if(count($this->prodotti_servizi_finalita_mm) > 0){
                if($having != '') $having .= ' AND ';
                $having .= 'COUNT(DISTINCT finalita_id) = ' . count($this->prodotti_servizi_finalita_mm);
            }
            $query->groupBy('prodotti_servizi.id')
                ->having($having);
        }
        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'financial_description', $this->financial_description])
            ->andFilterWhere(['like', 'facilitation_features', $this->facilitation_features])
            ->andFilterWhere(['like', 'projects_features', $this->projects_features])
            ->andFilterWhere(['like', 'expenses_eligible', $this->expenses_eligible]);

        return $dataProvider;
    }
}
