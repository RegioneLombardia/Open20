<?php

namespace open20\video\models\search;

use open20\amos\core\interfaces\ContentModelSearchInterface;
use open20\amos\core\interfaces\SearchModelInterface;
use open20\video\utils\VideoUtil;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\video\models\Video;

/**
 * FormazioneSearch represents the model behind the search form about `frontend\modules\formazione\models\Formazione`.
 */
class VideoSearch extends Video implements SearchModelInterface, ContentModelSearchInterface {

    public function __construct(array $config = []) {
        $this->isSearch = true;
        parent::__construct($config);
    }

    public function rules() {
        return [
            [['id', 'in_evidenza', 'community_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['title', 'url', 'description', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            ['Community', 'safe'],
        ];
    }

    public function scenarios() {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @inheritdoc
     */
    public function searchOwnInterestsQuery($params) {
        return $this->buildQuery($params, 'own-interest');
    }

    /**
     * @inheritdoc
     */
    public function searchAllQuery($params) {
        return $this->buildQuery($params, 'all');
    }

    public function search($params, $limit = null) {
        $query = Video::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if (!is_null($limit)) {
            $query->limit($limit);
            $query->orderBy('id DESC');
        } else {
            $query->orderBy('title ASC');
        }

        $dataProvider->setSort([
            'attributes' => [
                'template' => [
                    'asc' => ['video.template' => SORT_ASC],
                    'desc' => ['video.template' => SORT_DESC],
                ],
                'vendorPath' => [
                    'asc' => ['video.vendorPath' => SORT_ASC],
                    'desc' => ['video.vendorPath' => SORT_DESC],
                ],
                'providerList' => [
                    'asc' => ['video.providerList' => SORT_ASC],
                    'desc' => ['video.providerList' => SORT_DESC],
                ],
                'actionButtonClass' => [
                    'asc' => ['video.actionButtonClass' => SORT_ASC],
                    'desc' => ['video.actionButtonClass' => SORT_DESC],
                ],
                'viewPath' => [
                    'asc' => ['video.viewPath' => SORT_ASC],
                    'desc' => ['video.viewPath' => SORT_DESC],
                ],
                'pathPrefix' => [
                    'asc' => ['video.pathPrefix' => SORT_ASC],
                    'desc' => ['video.pathPrefix' => SORT_DESC],
                ],
                'savedForm' => [
                    'asc' => ['video.savedForm' => SORT_ASC],
                    'desc' => ['video.savedForm' => SORT_DESC],
                ],
                'formLayout' => [
                    'asc' => ['video.formLayout' => SORT_ASC],
                    'desc' => ['video.formLayout' => SORT_DESC],
                ],
                'accessFilter' => [
                    'asc' => ['video.accessFilter' => SORT_ASC],
                    'desc' => ['video.accessFilter' => SORT_DESC],
                ],
                'generateAccessFilterMigrations' => [
                    'asc' => ['video.generateAccessFilterMigrations' => SORT_ASC],
                    'desc' => ['video.generateAccessFilterMigrations' => SORT_DESC],
                ],
                'singularEntities' => [
                    'asc' => ['video.singularEntities' => SORT_ASC],
                    'desc' => ['video.singularEntities' => SORT_DESC],
                ],
                'modelMessageCategory' => [
                    'asc' => ['video.modelMessageCategory' => SORT_ASC],
                    'desc' => ['video.modelMessageCategory' => SORT_DESC],
                ],
                'controllerClass' => [
                    'asc' => ['video.controllerClass' => SORT_ASC],
                    'desc' => ['video.controllerClass' => SORT_DESC],
                ],
                'modelClass' => [
                    'asc' => ['video.modelClass' => SORT_ASC],
                    'desc' => ['video.modelClass' => SORT_DESC],
                ],
                'searchModelClass' => [
                    'asc' => ['video.searchModelClass' => SORT_ASC],
                    'desc' => ['video.searchModelClass' => SORT_DESC],
                ],
                'baseControllerClass' => [
                    'asc' => ['video.baseControllerClass' => SORT_ASC],
                    'desc' => ['video.baseControllerClass' => SORT_DESC],
                ],
                'indexWidgetType' => [
                    'asc' => ['video.indexWidgetType' => SORT_ASC],
                    'desc' => ['video.indexWidgetType' => SORT_DESC],
                ],
                'enableI18N' => [
                    'asc' => ['video.enableI18N' => SORT_ASC],
                    'desc' => ['video.enableI18N' => SORT_DESC],
                ],
                'enablePjax' => [
                    'asc' => ['video.enablePjax' => SORT_ASC],
                    'desc' => ['video.enablePjax' => SORT_DESC],
                ],
                'messageCategory' => [
                    'asc' => ['video.messageCategory' => SORT_ASC],
                    'desc' => ['video.messageCategory' => SORT_DESC],
                ],
                'formTabs' => [
                    'asc' => ['video.formTabs' => SORT_ASC],
                    'desc' => ['video.formTabs' => SORT_DESC],
                ],
                'tabsFieldList' => [
                    'asc' => ['video.tabsFieldList' => SORT_ASC],
                    'desc' => ['video.tabsFieldList' => SORT_DESC],
                ],
                'relFiledsDynamic' => [
                    'asc' => ['video.relFiledsDynamic' => SORT_ASC],
                    'desc' => ['video.relFiledsDynamic' => SORT_DESC],
                ],
        ]]);

        $scope = VideoUtil::getScopeCommunity();
        if ($scope) {
            $query->andWhere(['community_id' => $scope]);
        }

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if ($params['VideoSearch']['in_evidenza']) {
            $query->andWhere(['in_evidenza' => $params['VideoSearch']['in_evidenza']]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'in_evidenza' => $this->in_evidenza,
            //'community_id' => $this->community_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'url', $this->url])
                ->andFilterWhere(['like', 'description', $this->description]);
// var_dump($dataProvider->query->createCommand()->rawSql);die;
        return $dataProvider;
    }

}
