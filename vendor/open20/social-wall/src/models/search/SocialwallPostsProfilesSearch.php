<?php

namespace open20\socialwall\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\socialwall\models\SocialwallPostsProfiles;

/**
 * SocialwallPostsProfilesSearch represents the model behind the search form about `open20\socialwall\models\SocialwallPostsProfiles`.
 */
class SocialwallPostsProfilesSearch extends SocialwallPostsProfiles
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
            [['type', 'profile_name', 'profile_url', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params, $queryType = null, $limit = null, $onlyDrafts = false, $pageSize = null)
    {
        $query = SocialwallPostsProfiles::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $dataProvider->setSort([
            'attributes' => [
                'template' => [
                    'asc' => ['socialwall_posts_profiles.template' => SORT_ASC],
                    'desc' => ['socialwall_posts_profiles.template' => SORT_DESC],
                ],
                'vendorPath' => [
                    'asc' => ['socialwall_posts_profiles.vendorPath' => SORT_ASC],
                    'desc' => ['socialwall_posts_profiles.vendorPath' => SORT_DESC],
                ],
                'providerList' => [
                    'asc' => ['socialwall_posts_profiles.providerList' => SORT_ASC],
                    'desc' => ['socialwall_posts_profiles.providerList' => SORT_DESC],
                ],
                'actionButtonClass' => [
                    'asc' => ['socialwall_posts_profiles.actionButtonClass' => SORT_ASC],
                    'desc' => ['socialwall_posts_profiles.actionButtonClass' => SORT_DESC],
                ],
                'viewPath' => [
                    'asc' => ['socialwall_posts_profiles.viewPath' => SORT_ASC],
                    'desc' => ['socialwall_posts_profiles.viewPath' => SORT_DESC],
                ],
                'pathPrefix' => [
                    'asc' => ['socialwall_posts_profiles.pathPrefix' => SORT_ASC],
                    'desc' => ['socialwall_posts_profiles.pathPrefix' => SORT_DESC],
                ],
                'savedForm' => [
                    'asc' => ['socialwall_posts_profiles.savedForm' => SORT_ASC],
                    'desc' => ['socialwall_posts_profiles.savedForm' => SORT_DESC],
                ],
                'formLayout' => [
                    'asc' => ['socialwall_posts_profiles.formLayout' => SORT_ASC],
                    'desc' => ['socialwall_posts_profiles.formLayout' => SORT_DESC],
                ],
                'accessFilter' => [
                    'asc' => ['socialwall_posts_profiles.accessFilter' => SORT_ASC],
                    'desc' => ['socialwall_posts_profiles.accessFilter' => SORT_DESC],
                ],
                'generateAccessFilterMigrations' => [
                    'asc' => ['socialwall_posts_profiles.generateAccessFilterMigrations' => SORT_ASC],
                    'desc' => ['socialwall_posts_profiles.generateAccessFilterMigrations' => SORT_DESC],
                ],
                'singularEntities' => [
                    'asc' => ['socialwall_posts_profiles.singularEntities' => SORT_ASC],
                    'desc' => ['socialwall_posts_profiles.singularEntities' => SORT_DESC],
                ],
                'modelMessageCategory' => [
                    'asc' => ['socialwall_posts_profiles.modelMessageCategory' => SORT_ASC],
                    'desc' => ['socialwall_posts_profiles.modelMessageCategory' => SORT_DESC],
                ],
                'controllerClass' => [
                    'asc' => ['socialwall_posts_profiles.controllerClass' => SORT_ASC],
                    'desc' => ['socialwall_posts_profiles.controllerClass' => SORT_DESC],
                ],
                'modelClass' => [
                    'asc' => ['socialwall_posts_profiles.modelClass' => SORT_ASC],
                    'desc' => ['socialwall_posts_profiles.modelClass' => SORT_DESC],
                ],
                'searchModelClass' => [
                    'asc' => ['socialwall_posts_profiles.searchModelClass' => SORT_ASC],
                    'desc' => ['socialwall_posts_profiles.searchModelClass' => SORT_DESC],
                ],
                'baseControllerClass' => [
                    'asc' => ['socialwall_posts_profiles.baseControllerClass' => SORT_ASC],
                    'desc' => ['socialwall_posts_profiles.baseControllerClass' => SORT_DESC],
                ],
                'indexWidgetType' => [
                    'asc' => ['socialwall_posts_profiles.indexWidgetType' => SORT_ASC],
                    'desc' => ['socialwall_posts_profiles.indexWidgetType' => SORT_DESC],
                ],
                'enableI18N' => [
                    'asc' => ['socialwall_posts_profiles.enableI18N' => SORT_ASC],
                    'desc' => ['socialwall_posts_profiles.enableI18N' => SORT_DESC],
                ],
                'enablePjax' => [
                    'asc' => ['socialwall_posts_profiles.enablePjax' => SORT_ASC],
                    'desc' => ['socialwall_posts_profiles.enablePjax' => SORT_DESC],
                ],
                'messageCategory' => [
                    'asc' => ['socialwall_posts_profiles.messageCategory' => SORT_ASC],
                    'desc' => ['socialwall_posts_profiles.messageCategory' => SORT_DESC],
                ],
                'formTabs' => [
                    'asc' => ['socialwall_posts_profiles.formTabs' => SORT_ASC],
                    'desc' => ['socialwall_posts_profiles.formTabs' => SORT_DESC],
                ],
                'tabsFieldList' => [
                    'asc' => ['socialwall_posts_profiles.tabsFieldList' => SORT_ASC],
                    'desc' => ['socialwall_posts_profiles.tabsFieldList' => SORT_DESC],
                ],
                'relFiledsDynamic' => [
                    'asc' => ['socialwall_posts_profiles.relFiledsDynamic' => SORT_ASC],
                    'desc' => ['socialwall_posts_profiles.relFiledsDynamic' => SORT_DESC],
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

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'profile_name', $this->profile_name])
            ->andFilterWhere(['like', 'profile_url', $this->profile_url]);

        return $dataProvider;
    }
}
