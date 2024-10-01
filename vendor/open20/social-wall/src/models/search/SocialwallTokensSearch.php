<?php

namespace open20\socialwall\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\socialwall\models\SocialwallTokens;

/**
 * SocialwallTokensModelSearch represents the model behind the search form about `open20\socialwall\models\SocialwallTokens`.
 */
class SocialwallTokensSearch extends SocialwallTokens
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
            [['name', 'type', 'data', 'secret', 'expires_at', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = SocialwallTokens::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $dataProvider->setSort([
            'attributes' => [
                'template' => [
                    'asc' => ['socialwall_tokens.template' => SORT_ASC],
                    'desc' => ['socialwall_tokens.template' => SORT_DESC],
                ],
                'vendorPath' => [
                    'asc' => ['socialwall_tokens.vendorPath' => SORT_ASC],
                    'desc' => ['socialwall_tokens.vendorPath' => SORT_DESC],
                ],
                'providerList' => [
                    'asc' => ['socialwall_tokens.providerList' => SORT_ASC],
                    'desc' => ['socialwall_tokens.providerList' => SORT_DESC],
                ],
                'actionButtonClass' => [
                    'asc' => ['socialwall_tokens.actionButtonClass' => SORT_ASC],
                    'desc' => ['socialwall_tokens.actionButtonClass' => SORT_DESC],
                ],
                'viewPath' => [
                    'asc' => ['socialwall_tokens.viewPath' => SORT_ASC],
                    'desc' => ['socialwall_tokens.viewPath' => SORT_DESC],
                ],
                'pathPrefix' => [
                    'asc' => ['socialwall_tokens.pathPrefix' => SORT_ASC],
                    'desc' => ['socialwall_tokens.pathPrefix' => SORT_DESC],
                ],
                'savedForm' => [
                    'asc' => ['socialwall_tokens.savedForm' => SORT_ASC],
                    'desc' => ['socialwall_tokens.savedForm' => SORT_DESC],
                ],
                'formLayout' => [
                    'asc' => ['socialwall_tokens.formLayout' => SORT_ASC],
                    'desc' => ['socialwall_tokens.formLayout' => SORT_DESC],
                ],
                'accessFilter' => [
                    'asc' => ['socialwall_tokens.accessFilter' => SORT_ASC],
                    'desc' => ['socialwall_tokens.accessFilter' => SORT_DESC],
                ],
                'generateAccessFilterMigrations' => [
                    'asc' => ['socialwall_tokens.generateAccessFilterMigrations' => SORT_ASC],
                    'desc' => ['socialwall_tokens.generateAccessFilterMigrations' => SORT_DESC],
                ],
                'singularEntities' => [
                    'asc' => ['socialwall_tokens.singularEntities' => SORT_ASC],
                    'desc' => ['socialwall_tokens.singularEntities' => SORT_DESC],
                ],
                'modelMessageCategory' => [
                    'asc' => ['socialwall_tokens.modelMessageCategory' => SORT_ASC],
                    'desc' => ['socialwall_tokens.modelMessageCategory' => SORT_DESC],
                ],
                'controllerClass' => [
                    'asc' => ['socialwall_tokens.controllerClass' => SORT_ASC],
                    'desc' => ['socialwall_tokens.controllerClass' => SORT_DESC],
                ],
                'modelClass' => [
                    'asc' => ['socialwall_tokens.modelClass' => SORT_ASC],
                    'desc' => ['socialwall_tokens.modelClass' => SORT_DESC],
                ],
                'searchModelClass' => [
                    'asc' => ['socialwall_tokens.searchModelClass' => SORT_ASC],
                    'desc' => ['socialwall_tokens.searchModelClass' => SORT_DESC],
                ],
                'baseControllerClass' => [
                    'asc' => ['socialwall_tokens.baseControllerClass' => SORT_ASC],
                    'desc' => ['socialwall_tokens.baseControllerClass' => SORT_DESC],
                ],
                'indexWidgetType' => [
                    'asc' => ['socialwall_tokens.indexWidgetType' => SORT_ASC],
                    'desc' => ['socialwall_tokens.indexWidgetType' => SORT_DESC],
                ],
                'enableI18N' => [
                    'asc' => ['socialwall_tokens.enableI18N' => SORT_ASC],
                    'desc' => ['socialwall_tokens.enableI18N' => SORT_DESC],
                ],
                'enablePjax' => [
                    'asc' => ['socialwall_tokens.enablePjax' => SORT_ASC],
                    'desc' => ['socialwall_tokens.enablePjax' => SORT_DESC],
                ],
                'messageCategory' => [
                    'asc' => ['socialwall_tokens.messageCategory' => SORT_ASC],
                    'desc' => ['socialwall_tokens.messageCategory' => SORT_DESC],
                ],
                'formTabs' => [
                    'asc' => ['socialwall_tokens.formTabs' => SORT_ASC],
                    'desc' => ['socialwall_tokens.formTabs' => SORT_DESC],
                ],
                'tabsFieldList' => [
                    'asc' => ['socialwall_tokens.tabsFieldList' => SORT_ASC],
                    'desc' => ['socialwall_tokens.tabsFieldList' => SORT_DESC],
                ],
                'relFiledsDynamic' => [
                    'asc' => ['socialwall_tokens.relFiledsDynamic' => SORT_ASC],
                    'desc' => ['socialwall_tokens.relFiledsDynamic' => SORT_DESC],
                ],
            ]]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        $query->andFilterWhere([
            'id' => $this->id,
            'expires_at' => $this->expires_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'data', $this->data])
            ->andFilterWhere(['like', 'secret', $this->secret]);

        return $dataProvider;
    }
}
