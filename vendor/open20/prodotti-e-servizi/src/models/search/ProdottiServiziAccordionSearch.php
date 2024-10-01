<?php

namespace open20\prodottiservizi\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\prodottiservizi\models\ProdottiServiziAccordion;
use yii\helpers\ArrayHelper;

/**
 * ProdottiServiziAccordionSearch represents the model behind the search form about `open20\prodottiservizi\models\ProdottiServiziAccordion`.
 */
class ProdottiServiziAccordionSearch extends ProdottiServiziAccordion
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
            [['id', 'is_visible', 'position', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['title', 'description', 'prodotto_id', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = ProdottiServiziAccordion::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'template' => [
                    'asc' => ['prodotti_servizi_accordion.template' => SORT_ASC],
                    'desc' => ['prodotti_servizi_accordion.template' => SORT_DESC],
                ],
                'vendorPath' => [
                    'asc' => ['prodotti_servizi_accordion.vendorPath' => SORT_ASC],
                    'desc' => ['prodotti_servizi_accordion.vendorPath' => SORT_DESC],
                ],
                'providerList' => [
                    'asc' => ['prodotti_servizi_accordion.providerList' => SORT_ASC],
                    'desc' => ['prodotti_servizi_accordion.providerList' => SORT_DESC],
                ],
                'actionButtonClass' => [
                    'asc' => ['prodotti_servizi_accordion.actionButtonClass' => SORT_ASC],
                    'desc' => ['prodotti_servizi_accordion.actionButtonClass' => SORT_DESC],
                ],
                'viewPath' => [
                    'asc' => ['prodotti_servizi_accordion.viewPath' => SORT_ASC],
                    'desc' => ['prodotti_servizi_accordion.viewPath' => SORT_DESC],
                ],
                'pathPrefix' => [
                    'asc' => ['prodotti_servizi_accordion.pathPrefix' => SORT_ASC],
                    'desc' => ['prodotti_servizi_accordion.pathPrefix' => SORT_DESC],
                ],
                'savedForm' => [
                    'asc' => ['prodotti_servizi_accordion.savedForm' => SORT_ASC],
                    'desc' => ['prodotti_servizi_accordion.savedForm' => SORT_DESC],
                ],
                'formLayout' => [
                    'asc' => ['prodotti_servizi_accordion.formLayout' => SORT_ASC],
                    'desc' => ['prodotti_servizi_accordion.formLayout' => SORT_DESC],
                ],
                'accessFilter' => [
                    'asc' => ['prodotti_servizi_accordion.accessFilter' => SORT_ASC],
                    'desc' => ['prodotti_servizi_accordion.accessFilter' => SORT_DESC],
                ],
                'generateAccessFilterMigrations' => [
                    'asc' => ['prodotti_servizi_accordion.generateAccessFilterMigrations' => SORT_ASC],
                    'desc' => ['prodotti_servizi_accordion.generateAccessFilterMigrations' => SORT_DESC],
                ],
                'singularEntities' => [
                    'asc' => ['prodotti_servizi_accordion.singularEntities' => SORT_ASC],
                    'desc' => ['prodotti_servizi_accordion.singularEntities' => SORT_DESC],
                ],
                'modelMessageCategory' => [
                    'asc' => ['prodotti_servizi_accordion.modelMessageCategory' => SORT_ASC],
                    'desc' => ['prodotti_servizi_accordion.modelMessageCategory' => SORT_DESC],
                ],
                'controllerClass' => [
                    'asc' => ['prodotti_servizi_accordion.controllerClass' => SORT_ASC],
                    'desc' => ['prodotti_servizi_accordion.controllerClass' => SORT_DESC],
                ],
                'modelClass' => [
                    'asc' => ['prodotti_servizi_accordion.modelClass' => SORT_ASC],
                    'desc' => ['prodotti_servizi_accordion.modelClass' => SORT_DESC],
                ],
                'searchModelClass' => [
                    'asc' => ['prodotti_servizi_accordion.searchModelClass' => SORT_ASC],
                    'desc' => ['prodotti_servizi_accordion.searchModelClass' => SORT_DESC],
                ],
                'baseControllerClass' => [
                    'asc' => ['prodotti_servizi_accordion.baseControllerClass' => SORT_ASC],
                    'desc' => ['prodotti_servizi_accordion.baseControllerClass' => SORT_DESC],
                ],
                'indexWidgetType' => [
                    'asc' => ['prodotti_servizi_accordion.indexWidgetType' => SORT_ASC],
                    'desc' => ['prodotti_servizi_accordion.indexWidgetType' => SORT_DESC],
                ],
                'enableI18N' => [
                    'asc' => ['prodotti_servizi_accordion.enableI18N' => SORT_ASC],
                    'desc' => ['prodotti_servizi_accordion.enableI18N' => SORT_DESC],
                ],
                'enablePjax' => [
                    'asc' => ['prodotti_servizi_accordion.enablePjax' => SORT_ASC],
                    'desc' => ['prodotti_servizi_accordion.enablePjax' => SORT_DESC],
                ],
                'messageCategory' => [
                    'asc' => ['prodotti_servizi_accordion.messageCategory' => SORT_ASC],
                    'desc' => ['prodotti_servizi_accordion.messageCategory' => SORT_DESC],
                ],
                'formTabs' => [
                    'asc' => ['prodotti_servizi_accordion.formTabs' => SORT_ASC],
                    'desc' => ['prodotti_servizi_accordion.formTabs' => SORT_DESC],
                ],
                'tabsFieldList' => [
                    'asc' => ['prodotti_servizi_accordion.tabsFieldList' => SORT_ASC],
                    'desc' => ['prodotti_servizi_accordion.tabsFieldList' => SORT_DESC],
                ],
                'relFiledsDynamic' => [
                    'asc' => ['prodotti_servizi_accordion.relFiledsDynamic' => SORT_ASC],
                    'desc' => ['prodotti_servizi_accordion.relFiledsDynamic' => SORT_DESC],
                ],
            ]]);
        
        $query->orderBy('position');

        if (!($this->load($params) && $this->validate())) {
            $query->andWhere(['prodotto_id' => null]);
            $query->andWhere(['created_by' => Yii::$app->user->id]);
            return $dataProvider;
        }

        $query->andWhere(['prodotto_id' => $this->prodotto_id]);

        $query->andFilterWhere([
            'id' => $this->id,
            'is_visible' => $this->is_visible,
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
