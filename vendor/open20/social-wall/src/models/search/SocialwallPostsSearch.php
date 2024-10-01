<?php

namespace open20\socialwall\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\socialwall\models\SocialwallPosts;
use yii\helpers\VarDumper;

/**
 * SocialwallPostsSearch represents the model behind the search form about `open20\socialwall\models\SocialwallPosts`.
 */
class SocialwallPostsSearch extends SocialwallPosts
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
            [['type', 'profile_name', 'text', 'permalink', 'publication_date', 'profile_url', 'media_type', 'created_at', 'updated_at', 'deleted_at', 'search_tags', 'fromPublicationDate', 'searchPostTags'], 'safe'],
        ];
    }

    public function scenarios()
    {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params, $queryType = null, $limit = null, $onlyDrafts = false, $pageSize = null)
    {
        $query = SocialwallPosts::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'template' => [
                    'asc' => ['socialwall_posts.template' => SORT_ASC],
                    'desc' => ['socialwall_posts.template' => SORT_DESC],
                ],
                'vendorPath' => [
                    'asc' => ['socialwall_posts.vendorPath' => SORT_ASC],
                    'desc' => ['socialwall_posts.vendorPath' => SORT_DESC],
                ],
                'providerList' => [
                    'asc' => ['socialwall_posts.providerList' => SORT_ASC],
                    'desc' => ['socialwall_posts.providerList' => SORT_DESC],
                ],
                'actionButtonClass' => [
                    'asc' => ['socialwall_posts.actionButtonClass' => SORT_ASC],
                    'desc' => ['socialwall_posts.actionButtonClass' => SORT_DESC],
                ],
                'viewPath' => [
                    'asc' => ['socialwall_posts.viewPath' => SORT_ASC],
                    'desc' => ['socialwall_posts.viewPath' => SORT_DESC],
                ],
                'pathPrefix' => [
                    'asc' => ['socialwall_posts.pathPrefix' => SORT_ASC],
                    'desc' => ['socialwall_posts.pathPrefix' => SORT_DESC],
                ],
                'savedForm' => [
                    'asc' => ['socialwall_posts.savedForm' => SORT_ASC],
                    'desc' => ['socialwall_posts.savedForm' => SORT_DESC],
                ],
                'formLayout' => [
                    'asc' => ['socialwall_posts.formLayout' => SORT_ASC],
                    'desc' => ['socialwall_posts.formLayout' => SORT_DESC],
                ],
                'accessFilter' => [
                    'asc' => ['socialwall_posts.accessFilter' => SORT_ASC],
                    'desc' => ['socialwall_posts.accessFilter' => SORT_DESC],
                ],
                'generateAccessFilterMigrations' => [
                    'asc' => ['socialwall_posts.generateAccessFilterMigrations' => SORT_ASC],
                    'desc' => ['socialwall_posts.generateAccessFilterMigrations' => SORT_DESC],
                ],
                'singularEntities' => [
                    'asc' => ['socialwall_posts.singularEntities' => SORT_ASC],
                    'desc' => ['socialwall_posts.singularEntities' => SORT_DESC],
                ],
                'modelMessageCategory' => [
                    'asc' => ['socialwall_posts.modelMessageCategory' => SORT_ASC],
                    'desc' => ['socialwall_posts.modelMessageCategory' => SORT_DESC],
                ],
                'controllerClass' => [
                    'asc' => ['socialwall_posts.controllerClass' => SORT_ASC],
                    'desc' => ['socialwall_posts.controllerClass' => SORT_DESC],
                ],
                'modelClass' => [
                    'asc' => ['socialwall_posts.modelClass' => SORT_ASC],
                    'desc' => ['socialwall_posts.modelClass' => SORT_DESC],
                ],
                'searchModelClass' => [
                    'asc' => ['socialwall_posts.searchModelClass' => SORT_ASC],
                    'desc' => ['socialwall_posts.searchModelClass' => SORT_DESC],
                ],
                'baseControllerClass' => [
                    'asc' => ['socialwall_posts.baseControllerClass' => SORT_ASC],
                    'desc' => ['socialwall_posts.baseControllerClass' => SORT_DESC],
                ],
                'indexWidgetType' => [
                    'asc' => ['socialwall_posts.indexWidgetType' => SORT_ASC],
                    'desc' => ['socialwall_posts.indexWidgetType' => SORT_DESC],
                ],
                'enableI18N' => [
                    'asc' => ['socialwall_posts.enableI18N' => SORT_ASC],
                    'desc' => ['socialwall_posts.enableI18N' => SORT_DESC],
                ],
                'enablePjax' => [
                    'asc' => ['socialwall_posts.enablePjax' => SORT_ASC],
                    'desc' => ['socialwall_posts.enablePjax' => SORT_DESC],
                ],
                'messageCategory' => [
                    'asc' => ['socialwall_posts.messageCategory' => SORT_ASC],
                    'desc' => ['socialwall_posts.messageCategory' => SORT_DESC],
                ],
                'formTabs' => [
                    'asc' => ['socialwall_posts.formTabs' => SORT_ASC],
                    'desc' => ['socialwall_posts.formTabs' => SORT_DESC],
                ],
                'tabsFieldList' => [
                    'asc' => ['socialwall_posts.tabsFieldList' => SORT_ASC],
                    'desc' => ['socialwall_posts.tabsFieldList' => SORT_DESC],
                ],
                'relFiledsDynamic' => [
                    'asc' => ['socialwall_posts.relFiledsDynamic' => SORT_ASC],
                    'desc' => ['socialwall_posts.relFiledsDynamic' => SORT_DESC],
                ],
            ]]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        $query->andFilterWhere([
            'id' => $this->id,
            'publication_date' => $this->publication_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['in', 'type', $this->type])
            ->andFilterWhere(['like', 'profile_name', $this->profile_name])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'search_tags', $this->search_tags])
            ->andFilterWhere(['like', 'permalink', $this->permalink])
            ->andFilterWhere(['like', 'profile_url', $this->profile_url]);

        if(!empty($this->searchPostTags)) {
            $tagsList = explode(",", $this->searchPostTags);
            $listConditions = [];
            foreach ($tagsList as $tag) {
                $listConditions[] = [
                    'and',
                    new \yii\db\Expression("find_in_set('" . $tag . "', socialwall_posts.search_tags)")
                ];
            }
            $conditions = array_merge(['or'], $listConditions);
            $query->andFilterWhere($conditions);
        }

        if(!empty($this->fromPublicationDate)) {
            $query->andFilterWhere(['>=', 'publication_date', date("Y-m-d", strtotime($this->fromPublicationDate))]);
        }

        return $dataProvider;
    }
}
