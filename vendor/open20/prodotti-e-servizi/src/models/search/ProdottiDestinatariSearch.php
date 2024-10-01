<?php

namespace open20\prodottiservizi\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\prodottiservizi\models\ProdottiDestinatari;

/**
 * ProdottiDestinatariSearch represents the model behind the search form about `open20\prodottiservizi\models\ProdottiDestinatari`.
 */
class ProdottiDestinatariSearch extends ProdottiDestinatari
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
            [['parent'], 'each', 'rule' => ['integer']],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = ProdottiDestinatari::find();
        $query->orderBy('name ASC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $dataProvider->setSort([
            'attributes' => [
                'template' => [
                    'asc' => ['prodotti_destinatari.template' => SORT_ASC],
                    'desc' => ['prodotti_destinatari.template' => SORT_DESC],
                ],
                'vendorPath' => [
                    'asc' => ['prodotti_destinatari.vendorPath' => SORT_ASC],
                    'desc' => ['prodotti_destinatari.vendorPath' => SORT_DESC],
                ],
                'providerList' => [
                    'asc' => ['prodotti_destinatari.providerList' => SORT_ASC],
                    'desc' => ['prodotti_destinatari.providerList' => SORT_DESC],
                ],
                'actionButtonClass' => [
                    'asc' => ['prodotti_destinatari.actionButtonClass' => SORT_ASC],
                    'desc' => ['prodotti_destinatari.actionButtonClass' => SORT_DESC],
                ],
                'viewPath' => [
                    'asc' => ['prodotti_destinatari.viewPath' => SORT_ASC],
                    'desc' => ['prodotti_destinatari.viewPath' => SORT_DESC],
                ],
                'pathPrefix' => [
                    'asc' => ['prodotti_destinatari.pathPrefix' => SORT_ASC],
                    'desc' => ['prodotti_destinatari.pathPrefix' => SORT_DESC],
                ],
                'savedForm' => [
                    'asc' => ['prodotti_destinatari.savedForm' => SORT_ASC],
                    'desc' => ['prodotti_destinatari.savedForm' => SORT_DESC],
                ],
                'formLayout' => [
                    'asc' => ['prodotti_destinatari.formLayout' => SORT_ASC],
                    'desc' => ['prodotti_destinatari.formLayout' => SORT_DESC],
                ],
                'accessFilter' => [
                    'asc' => ['prodotti_destinatari.accessFilter' => SORT_ASC],
                    'desc' => ['prodotti_destinatari.accessFilter' => SORT_DESC],
                ],
                'generateAccessFilterMigrations' => [
                    'asc' => ['prodotti_destinatari.generateAccessFilterMigrations' => SORT_ASC],
                    'desc' => ['prodotti_destinatari.generateAccessFilterMigrations' => SORT_DESC],
                ],
                'singularEntities' => [
                    'asc' => ['prodotti_destinatari.singularEntities' => SORT_ASC],
                    'desc' => ['prodotti_destinatari.singularEntities' => SORT_DESC],
                ],
                'modelMessageCategory' => [
                    'asc' => ['prodotti_destinatari.modelMessageCategory' => SORT_ASC],
                    'desc' => ['prodotti_destinatari.modelMessageCategory' => SORT_DESC],
                ],
                'controllerClass' => [
                    'asc' => ['prodotti_destinatari.controllerClass' => SORT_ASC],
                    'desc' => ['prodotti_destinatari.controllerClass' => SORT_DESC],
                ],
                'modelClass' => [
                    'asc' => ['prodotti_destinatari.modelClass' => SORT_ASC],
                    'desc' => ['prodotti_destinatari.modelClass' => SORT_DESC],
                ],
                'searchModelClass' => [
                    'asc' => ['prodotti_destinatari.searchModelClass' => SORT_ASC],
                    'desc' => ['prodotti_destinatari.searchModelClass' => SORT_DESC],
                ],
                'baseControllerClass' => [
                    'asc' => ['prodotti_destinatari.baseControllerClass' => SORT_ASC],
                    'desc' => ['prodotti_destinatari.baseControllerClass' => SORT_DESC],
                ],
                'indexWidgetType' => [
                    'asc' => ['prodotti_destinatari.indexWidgetType' => SORT_ASC],
                    'desc' => ['prodotti_destinatari.indexWidgetType' => SORT_DESC],
                ],
                'enableI18N' => [
                    'asc' => ['prodotti_destinatari.enableI18N' => SORT_ASC],
                    'desc' => ['prodotti_destinatari.enableI18N' => SORT_DESC],
                ],
                'enablePjax' => [
                    'asc' => ['prodotti_destinatari.enablePjax' => SORT_ASC],
                    'desc' => ['prodotti_destinatari.enablePjax' => SORT_DESC],
                ],
                'messageCategory' => [
                    'asc' => ['prodotti_destinatari.messageCategory' => SORT_ASC],
                    'desc' => ['prodotti_destinatari.messageCategory' => SORT_DESC],
                ],
                'formTabs' => [
                    'asc' => ['prodotti_destinatari.formTabs' => SORT_ASC],
                    'desc' => ['prodotti_destinatari.formTabs' => SORT_DESC],
                ],
                'tabsFieldList' => [
                    'asc' => ['prodotti_destinatari.tabsFieldList' => SORT_ASC],
                    'desc' => ['prodotti_destinatari.tabsFieldList' => SORT_DESC],
                ],
                'relFiledsDynamic' => [
                    'asc' => ['prodotti_destinatari.relFiledsDynamic' => SORT_ASC],
                    'desc' => ['prodotti_destinatari.relFiledsDynamic' => SORT_DESC],
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

        $query->andFilterWhere(
            ['in', 'parent', $this->parent]
        );

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'icon', $this->icon]);

        return $dataProvider;
    }
}
