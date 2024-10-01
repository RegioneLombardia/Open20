<?php

namespace open20\amos\collaborations\models\search;

use open20\amos\admin\models\UserProfile;
use open20\amos\collaborations\models\base\CollaborationProposals;
use open20\amos\core\interfaces\CmsModelInterface;
use open20\amos\core\interfaces\SearchModelInterface;
use open20\amos\core\record\CmsField;
use open20\amos\core\record\ContentModel;
use open20\amos\cwh\query\CwhActiveQuery;
use open20\amos\myactivities\basic\MyActivitiesModelsInterface;
use open20\amos\workflow\behaviors\WorkflowLogFunctionsBehavior;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use open20\amos\collaborations\models\CollaborationProposals as CollaborationProposalsModel;
use open20\amos\tag\models\EntitysTagsMm;
use open20\amos\core\record\SearchResult;
use yii\db\ActiveQuery;
use yii\di\Container;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use yii\helpers\Url;

/**
 * CollaborationProposalsSearch represents the model behind the search form
 * about `open20\amos\collaborations\models\CollaborationProposalsSearch`.
 *
 * @property-write mixed $sort
 */
class CollaborationProposalsSearch extends CollaborationProposalsModel implements SearchModelInterface, CmsModelInterface, MyActivitiesModelsInterface {

    //private $container;
    // Page size
    const PAGE_SIZE = 10;

    /**
     * @param array $config
     */
    public function __construct(array $config = []) {
        $this->isSearch = true;
        parent::__construct($config);
    }

    /**
     * Rules
     * @return array
     */
    public function rules() {
        return [
            [[
            'id', 'category_id', 'collaboration_type_id', 'stage_of_development_id', 'created_by', 'updated_by',
            'deleted_by', 'visible_to_guest', 'in_evidence'
                ], 'integer'],
            [[
            'title', 'short_summary', 'full_description', 'advantages', 'technical_specifications', 'partner_role',
            'caption', 'short_abstract', 'video_pitch', 'video_title', 'start_date', 'end_date', 'created_at',
            'updated_at', 'deleted_at', 'id_een', 'tagValuesGoal', 'status'
                ], 'safe'],
            [[
            'start_date_from', 'start_date_to', 'end_date_from', 'end_date_to',
                ], 'safe'],
            ['CollaborationStageOfDevelopment', 'safe'],
            ['CollaborationType', 'safe'],
        ];
    }

    /**
     * Scenarios
     * @return array|array[]
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        $parentBehaviors = parent::behaviors();

        $behaviors = [];
        // If the parent model CollaborationProposals is a model enabled for tags, CollaborationProposalsSearch will have TaggableBehavior too
        $moduleTag = \Yii::$app->getModule('tag');
        if (isset($moduleTag) && in_array('open20\amos\collaborations\models\CollaborationProposals', $moduleTag->modelsEnabled) && $moduleTag->behaviors) {
            $behaviors = ArrayHelper::merge($moduleTag->behaviors, $behaviors);
        }

        $behaviors['WorkflowLogFunctions'] = [
            'class' => WorkflowLogFunctionsBehavior::class
        ];

        return ArrayHelper::merge($parentBehaviors, $behaviors);
    }

    /**
     * Base search
     * @param $params
     * @return ActiveDataProvider
     * @throws InvalidConfigException
     */
    public function search($params, $queryType = null, $limit = null, $onlyDrafts = false, $pageSize = null) {
        $query = CollaborationProposalsModel::find();

        // Ordinamento dal più recente aggiornato al più vecchio di default (se non sono attivi altri ordinamenti)
        if (!strpos(Url::current(), 'sort')) {
            $query->orderBy(['updated_at' => SORT_DESC]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pagesize' => self::PAGE_SIZE]
        ]);

        $query->joinWith('collaborationStageOfDevelopment');
        $query->joinWith('collaborationType');
        $query->joinWith('entitysTagsMms')->distinct();

        $this->setSort($dataProvider);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // Filters
        $this->baseFilters($query);
        $this->filterByOnuGoalsTags($query);
        $this->filterByStatus($query);
        $this->filterByTags($query, $params);

        return $dataProvider;
    }

    /**
     * Published proposals
     * @param $params
     * @return ActiveDataProvider
     * @throws InvalidConfigException
     */
    public function searchPublished($params) {
        $dataProvider = $this->search($params);
        $dataProvider->query->andWhere(['status' => self::STATUS_PUBLISHED])
                ->andWhere(['>=', 'end_date', date('Y-m-d')]);

        return $dataProvider;
    }

    /**
     * Published proposals visible to guests
     * @param $params
     * @return ActiveDataProvider
     * @throws InvalidConfigException
     */
    public function searchPublishedGuest($params) {
        $dataProvider = $this->searchPublished($params);
        $dataProvider->query->andWhere(['visible_to_guest' => self::VISIBLE_TO_GUEST]);

        return $dataProvider;
    }

    /**
     * Draft proposals
     * @param $params
     * @return ActiveDataProvider
     * @throws InvalidConfigException
     */
    public function searchToPublish($params) {
        $dataProvider = $this->search($params);
//        $dataProvider->query->andWhere(['status' => self::STATUS_DRAFT]);
        $dataProvider->query
                ->andWhere(['status' => self::STATUS_TOVALIDATE])
                ->andWhere(['>=', 'end_date', date('Y-m-d')]);

        return $dataProvider;
    }

    /**
     * Recupera le proposte di collaborazione in bozza
     * @param $params
     * @return ActiveDataProvider
     * @throws InvalidConfigException
     */
    public function searchDraft($params) {
        $dataProvider = $this->search($params);
        $dataProvider->query->andWhere(['status' => self::STATUS_DRAFT]);

        return $dataProvider;
    }

    /**
     * Recupera le proposte di collaborazione da validare
     * @param $params
     * @return ActiveDataProvider
     * @throws InvalidConfigException
     */
    public function searchToValidate($params, $limit = null) {
        $dataProvider = $this->search($params);
        $dataProvider->query
                ->andWhere(['status' => self::STATUS_TOVALIDATE])
                ->andWhere(['>=', 'end_date', date('Y-m-d')]);

        return $dataProvider;
    }

    /**
     * Recupera le proposte di collaborazione rifiutate
     * @param $params
     * @return ActiveDataProvider
     * @throws InvalidConfigException
     */
    public function searchRefused($params) {
        $dataProvider = $this->search($params);
        $dataProvider->query->andWhere(['status' => self::STATUS_REFUSED]);

        return $dataProvider;
    }

    /**
     * Created by logged user proposals
     * @param $params
     * @return ActiveDataProvider
     * @throws InvalidConfigException
     */
    public function searchCreatedByMe($params) {
        $dataProvider = $this->search($params);
        $dataProvider->query->andWhere(['collaboration_proposals.created_by' => \Yii::$app->user->id]);

        return $dataProvider;
    }

    /**
     * Archived proposals
     * @param $params
     * @return ActiveDataProvider
     * @throws InvalidConfigException
     */
    public function searchArchived($params) {
        $dataProvider = $this->search($params);
        $dataProvider->query->andWhere(['<', 'end_date', date('Y-m-d')])
                ->andWhere(['not in', 'status', [self::STATUS_CLOSED, self::STATUS_DRAFT]]);

        return $dataProvider;
    }

    /**
     * Closed proposals
     * @param $params
     * @return ActiveDataProvider
     * @throws InvalidConfigException
     */
    public function searchClosed($params) {
        $dataProvider = $this->search($params);
        $dataProvider->query->andWhere(['status' => self::STATUS_CLOSED]);

        return $dataProvider;
    }

    /**
     * Own interest published proposals
     * @param $params
     * @param $limit
     * @return ActiveDataProvider|\yii\data\BaseDataProvider
     * @throws InvalidConfigException
     */
    public function searchOwnInterest($params, $limit = null) {
        $dataProvider = ContentModel::search($params, 'own-interest', $limit, false, self::PAGE_SIZE);

        $dataProvider->query->joinWith('collaborationStageOfDevelopment');
        $dataProvider->query->joinWith('collaborationType');
        $dataProvider->query->joinWith('entitysTagsMms')->distinct();

        $dataProvider->query->andWhere(['status' => self::STATUS_PUBLISHED])
                ->andWhere(['>=', 'end_date', date('Y-m-d')])
                ->orderBy(['updated_at' => SORT_DESC]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // Filters
        $this->baseFilters($dataProvider->query);
        $this->filterByOnuGoalsTags($dataProvider->query);
        $this->filterByStatus($dataProvider->query);

        return $dataProvider;
    }

    /**
     * Published proposals visible to guest and in evidence
     * @param $params
     * @return ActiveDataProvider
     * @throws InvalidConfigException
     */
    public function searchVisibleToGuestAndInEvidence($params) {
        $dataProvider = $this->searchPublished($params);
        $dataProvider->query->andWhere(['collaboration_proposals.visible_to_guest' => self::VISIBLE_TO_GUEST])
                ->andWhere(['collaboration_proposals.in_evidence' => self::IN_EVIDENCE])
                ->limit(10);

        return $dataProvider;
    }

    /**
     * @param ActiveQuery $query
     * @param array $params
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    protected function filterByTags($query, $params) {
        $moduleTag = \Yii::$app->getModule('tag');
        $collaborationProposalsClassname = 'open20\amos\collaborations\models\CollaborationProposals';
        if (isset($moduleTag) && in_array($collaborationProposalsClassname, $moduleTag->modelsEnabled) && $moduleTag->behaviors) {
            if (isset($params[$this->formName()]['tagValues'])) {
                $tagValues = $params[$this->formName()]['tagValues'];
                $this->setTagValues($tagValues);
                if (is_array($tagValues) && !empty($tagValues)) {
                    $andWhere = "";
                    $i = 0;
                    foreach ($tagValues as $rootId => $tagId) {
                        if (!empty($tagId)) {
                            if ($i == 0) {
                                $query->innerJoin(EntitysTagsMm::tableName() . ' entities_tag',
                                        "entities_tag.classname = '" . addslashes($collaborationProposalsClassname) . "' AND entities_tag.record_id = " . CollaborationProposals::tableName() . ".id");
                            } else {
                                $andWhere .= " OR ";
                            }
                            $andWhere .= "(entities_tag.tag_id in (" . $tagId . ") AND entities_tag.root_id = " . $rootId . " AND entities_tag.deleted_at IS NULL)";
                            $i++;
                        }
                    }
                    if (!empty($andWhere)) {
                        $query->andWhere($andWhere);
                    }
                }
            }
        }
        return $query;
    }

    /**
     * Search all validated documents
     *
     * @param array $searchParamsArray Array of search words
     * @param int|null $pageSize
     * @return ActiveDataProvider
     * @throws InvalidConfigException
     */
    public function globalSearch($searchParamsArray, $pageSize = 5) {
        $dataProvider = $this->searchAll([]);
        $pagination = $dataProvider->getPagination();
        if (!$pagination) {
            $pagination = new Pagination();
            $dataProvider->setPagination($pagination);
        }
        $pagination->setPageSize($pageSize);

        // Verifico se il modulo supporta i TAG e, in caso, ricerco anche fra quelli
        $moduleTag = \Yii::$app->getModule('tag');
        $enableTagSearch = isset($moduleTag) && in_array($this->collaborationProposalsModule->model('CollaborationProposals'), $moduleTag->modelsEnabled);

        if ($enableTagSearch) {
            $dataProvider->query->leftJoin('entitys_tags_mm e_tag',
                    "e_tag.record_id=" . CollaborationProposals::tableName() . ".id AND e_tag.deleted_at IS NULL AND e_tag.classname='" . addslashes(CollaborationProposals::className()) . "'");

            $dataProvider->query->leftJoin('tag t', "e_tag.tag_id=t.id");
        }


        foreach ($searchParamsArray as $searchString) {
            $orQueries = [
                'or',
                ['like', self::tableName() . '.title', $searchString],
                ['like', self::tableName() . '.short_summary', $searchString],
                ['like', self::tableName() . '.full_description', $searchString],
                ['like', self::tableName() . '.advantages', $searchString],
                ['like', self::tableName() . '.technical_specifications', $searchString],
                ['like', self::tableName() . '.partner_role', $searchString],
            ];

            $tagsValues = \Yii::$app->request->get('tagValues');
            if ($enableTagSearch) {
                $arrayTagIds = [];
                if (!empty($tagsValues)) {
                    $tagIds = ArrayHelper::merge($arrayTagIds, explode(',', $tagsValues));
                    $dataProvider->query->andFilterWhere(['t.id' => $tagIds]);
                }
            } else {
                if (!empty($tagsValues)) {
                    $dataProvider->query->andWhere(0);
                }
            }

            $dataProvider->query->andWhere($orQueries);
        }

        $searchModels = [];
        foreach ($dataProvider->models as $m) {
            array_push($searchModels, $this->convertToSearchResult($m));
        }
        $dataProvider->setModels($searchModels);

        return $dataProvider;
    }

    /**
     * @param object $model The model to convert into SearchResult
     * @return SearchResult
     */
    public function convertToSearchResult($model) {
        $searchResult = new SearchResult();
        $searchResult->url = $model->getFullViewUrl();
        $searchResult->box_type = "none";
        $searchResult->id = $model->id;
        $searchResult->titolo = $model->title;
        $searchResult->data_pubblicazione = $model->start_date;
        $searchResult->abstract = $model->short_summary;
        return $searchResult;
    }

    /**
     * Search method useful to retrieve proposals to show in frontend (with cms)
     *
     * @param $params
     * @param int|null $limit
     * @return ActiveDataProvider
     * @throws InvalidConfigException
     */
    public function cmsSearch($params, $limit = null) {
        $params = array_merge($params, \Yii::$app->request->get());
        $this->load($params);
        // Base search
        $dataProviderOrig = $this->search($params);
//        $this->applySearchFilters($query);

        $dataProvider = new ActiveDataProvider([
            'query' => $dataProviderOrig->query,
            'key' => 'id',
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);

        $this->setCmsParams($dataProvider, $limit);

        return $dataProvider;
    }

    /**
     * @param $command
     * @param $query ActiveQuery
     */
    /* public function cmsFilterCategories($command, $query){
      $explode = explode('=>', $command);
      if(count($explode) == 2){
      $val = trim($explode[1]);
      $val = str_replace('[', '', $val);
      $val = str_replace(']', '', $val);
      $categoryIds = explode(',', $val);
      $query->leftJoin('partnership_profiles_category_mm', 'partnership_profiles_category_mm.partnership_profiles_id = partnership_profiles.id')
      ->andFilterWhere(['partnership_profiles_category_mm.partnership_profiles_category_id' => $categoryIds]);
      }
      } */

    /**
     * Cms search fields
     * @return array
     */
    public function cmsSearchFields() {
        $searchFields = [];
        $searchFields[] = new CmsField("title", "TEXT");
        $searchFields[] = new CmsField("short_summary", "TEXT");
        ;

        return $searchFields;
    }

    /**
     * Set cms visibility
     * @param int $id
     * @return boolean
     */
    public function cmsIsVisible($id) {
        return true;
    }

    /**
     * Cms view fields
     * @return array
     */
    public function cmsViewFields() {
        $viewFields = [];

//    array_push($viewFields, new CmsField("titolo", "TEXT", 'amosnews', $this->attributeLabels()["titolo"]));
//    array_push($viewFields, new CmsField("descrizione_breve", "TEXT", 'amosnews', $this->attributeLabels()['descrizione_breve']));
//    array_push($viewFields, new CmsField("newsImage", "IMAGE", 'amosnews', $this->attributeLabels()['newsImage']));
//    array_push($viewFields, new CmsField("data_pubblicazione", "DATE", 'amosnews', $this->attributeLabels()['data_pubblicazione']));

        $viewFields[] = new CmsField("title", "TEXT", 'amoscollaborations', $this->attributeLabels()["title"]);
        $viewFields[] = new CmsField("short_summary", "TEXT", 'amoscollaborations', $this->attributeLabels()['short_summary']);
//        $viewFields[] = new CmsField("data_pubblicazione", "DATE", 'amoscollaborations',
//            $this->attributeLabels()['data_pubblicazione']);

        return $viewFields;
    }

    /**
     * Per Guest visualizza SOLO le proposte pubblicate visibili per guest.
     * Per User visualizza le proposte pubblicate di suo interesse (compatibili con i tag di interesse).
     *
     * @param $params
     * @param int|null $limit
     * @return ActiveDataProvider
     * @throws InvalidConfigException
     */
    public function cmsSearchCollaborationOwnInterest($params, $limit = null) {
        $params = array_merge($params, \Yii::$app->request->get());
        $this->load($params);
        if (Yii::$app->user->isGuest) {
            return $this->cmsSearch($params, $limit);
        } else {
            $dataProvider = $this->searchOwnInterest($params);
//            $dataProvider->query->leftJoin(EntitysTagsMm::tableName(), EntitysTagsMm::tableName() . ".classname = '".  str_replace('\\','\\\\',CollaborationProposals::className()) . "' and ".EntitysTagsMm::tableName(). ".record_id = ". CollaborationProposals::tableName() . ".id  and " . EntitysTagsMm::tableName(). ".deleted_at is NULL");
//        $this->applySearchFilters($query);

            $this->setCmsParams($dataProvider, $limit);

            return $dataProvider;
        }
    }

    /**
     * Per Guest visualizza le proposte pubblicate visibili per guest & in evidenza.
     * Per User visualizza le proposte pubblicate di suo interesse (compatibili con i tag di interesse).
     *
     * @param $params
     * @param int|null $limit
     * @return ActiveDataProvider
     * @throws InvalidConfigException
     */
    public function cmsSearchCollaborationEvidenceOwnInterest($params, $limit = null) {
        $params = array_merge($params, \Yii::$app->request->get());
        $this->load($params);
        if (Yii::$app->user->isGuest) {
            $dataProvider = $this->searchVisibleToGuestAndInEvidence($params);
        } else {
            $dataProvider = $this->searchOwnInterest($params);
//            $dataProvider->query->leftJoin(EntitysTagsMm::tableName(), EntitysTagsMm::tableName() . ".classname = '".  str_replace('\\','\\\\',CollaborationProposals::className()) . "' and ".EntitysTagsMm::tableName(). ".record_id = ". CollaborationProposals::tableName() . ".id  and " . EntitysTagsMm::tableName(). ".deleted_at is NULL");
        }
//        $this->applySearchFilters($query);

        $this->setCmsParams($dataProvider, $limit);

        return $dataProvider;
    }

    /**
     * Filter by status workflow
     * @param $query
     * @return void
     */
    public function filterByStatus($query) {
        if (Yii::$app->controller->action->id == 'manage') {
            if (!empty($this->status)) {
                if ($this->status == 'CollaborationProposalsWorkflow/ARCHIVED') {
                    $query->andFilterWhere(['<', 'end_date', date('Y-m-d')]);
                } else {
                    $query->andFilterWhere(['like', 'status', $this->status])
                            ->andFilterWhere(['>=', 'end_date', date('Y-m-d')]);
                }
            }
        }
    }

    /**
     * Set sort
     * @param $dataProvider
     * @return void
     */
    public function setSort($dataProvider) {
        $dataProvider->setSort([
            'attributes' => [
                'title' => [
                    'asc' => ['collaboration_proposals.title' => SORT_ASC],
                    'desc' => ['collaboration_proposals.title' => SORT_DESC],
                ],
                'category_id' => [
                    'asc' => ['collaboration_proposals.category_id' => SORT_ASC],
                    'desc' => ['collaboration_proposals.category_id' => SORT_DESC],
                ],
                'collaboration_type_id' => [
                    'asc' => ['collaboration_proposals.collaboration_type_id' => SORT_ASC],
                    'desc' => ['collaboration_proposals.collaboration_type_id' => SORT_DESC],
                ],
                'short_summary' => [
                    'asc' => ['collaboration_proposals.short_summary' => SORT_ASC],
                    'desc' => ['collaboration_proposals.short_summary' => SORT_DESC],
                ],
                'full_description' => [
                    'asc' => ['collaboration_proposals.full_description' => SORT_ASC],
                    'desc' => ['collaboration_proposals.full_description' => SORT_DESC],
                ],
                'technical_specifications' => [
                    'asc' => ['collaboration_proposals.technical_specifications' => SORT_ASC],
                    'desc' => ['collaboration_proposals.technical_specifications' => SORT_DESC],
                ],
                'advantages' => [
                    'asc' => ['collaboration_proposals.advantages' => SORT_ASC],
                    'desc' => ['collaboration_proposals.advantages' => SORT_DESC],
                ],
                'stage_of_development_id' => [
                    'asc' => ['collaboration_proposals.stage_of_development_id' => SORT_ASC],
                    'desc' => ['collaboration_proposals.stage_of_development_id' => SORT_DESC],
                ],
                'partner_role' => [
                    'asc' => ['collaboration_proposals.partner_role' => SORT_ASC],
                    'desc' => ['collaboration_proposals.partner_role' => SORT_DESC],
                ],
                'caption' => [
                    'asc' => ['collaboration_proposals.caption' => SORT_ASC],
                    'desc' => ['collaboration_proposals.caption' => SORT_DESC],
                ],
                'short_abstract' => [
                    'asc' => ['collaboration_proposals.short_abstract' => SORT_ASC],
                    'desc' => ['collaboration_proposals.short_abstract' => SORT_DESC],
                ],
                'video_pitch' => [
                    'asc' => ['collaboration_proposals.video_pitch' => SORT_ASC],
                    'desc' => ['collaboration_proposals.video_pitch' => SORT_DESC],
                ],
                'video_title' => [
                    'asc' => ['collaboration_proposals.video_title' => SORT_ASC],
                    'desc' => ['collaboration_proposals.video_title' => SORT_DESC],
                ],
                'start_date' => [
                    'asc' => ['collaboration_proposals.start_date' => SORT_ASC],
                    'desc' => ['collaboration_proposals.start_date' => SORT_DESC],
                ],
                'end_date' => [
                    'asc' => ['collaboration_proposals.end_date' => SORT_ASC],
                    'desc' => ['collaboration_proposals.end_date' => SORT_DESC],
                ],
                'id_een' => [
                    'asc' => ['collaboration_proposals.id_een' => SORT_ASC],
                    'desc' => ['collaboration_proposals.id_een' => SORT_DESC],
                ],
                'visible_to_guest' => [
                    'asc' => ['collaboration_proposals.visible_to_guest' => SORT_ASC],
                    'desc' => ['collaboration_proposals.visible_to_guest' => SORT_DESC],
                ],
                'in_evidence' => [
                    'asc' => ['collaboration_proposals.in_evidence' => SORT_ASC],
                    'desc' => ['collaboration_proposals.in_evidence' => SORT_DESC],
                ],
                'status' => [
                    'asc' => ['collaboration_proposals.status' => SORT_ASC],
                    'desc' => ['collaboration_proposals.status' => SORT_DESC],
                ],
                'collaborationStageOfDevelopment' => [
                    'asc' => ['collaboration_stage_of_development.name' => SORT_ASC],
                    'desc' => ['collaboration_stage_of_development.name' => SORT_DESC],
                ],
                'collaborationType' => [
                    'asc' => ['collaboration_type.name' => SORT_ASC],
                    'desc' => ['collaboration_type.name' => SORT_DESC],
                ],
            ]
        ]);
    }

    /**
     * Base filters
     * @param $query
     * @return void
     */
    public function baseFilters($query) {
        $query->andFilterWhere([
            'id' => $this->id,
            'category_id' => $this->category_id,
            'collaboration_type_id' => $this->collaboration_type_id,
            'stage_of_development_id' => $this->stage_of_development_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'visible_to_guest' => $this->visible_to_guest,
            'in_evidence' => $this->in_evidence,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
            'deleted_by' => $this->deleted_by,
            'deleted_at' => $this->deleted_at
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'short_summary', $this->short_summary])
                ->andFilterWhere(['like', 'category_id', $this->category_id])
                ->andFilterWhere(['like', 'full_description', $this->full_description])
                ->andFilterWhere(['like', 'advantages', $this->advantages])
                ->andFilterWhere(['like', 'technical_specifications', $this->technical_specifications])
                ->andFilterWhere(['like', 'partner_role', $this->partner_role])
                ->andFilterWhere(['like', 'caption', $this->caption])
                ->andFilterWhere(['like', 'short_abstract', $this->short_abstract])
                ->andFilterWhere(['like', 'video_pitch', $this->video_pitch])
                ->andFilterWhere(['like', 'video_title', $this->video_title])
                ->andFilterWhere(['like', 'id_een', $this->id_een]);
        $query->andFilterWhere(['>=', 'start_date', $this->start_date_from]);
        $query->andFilterWhere(['<=', 'start_date', $this->start_date_to]);
        $query->andFilterWhere(['>=', 'end_date', $this->end_date_from]);
        $query->andFilterWhere(['<=', 'end_date', $this->end_date_to]);
        $query->andFilterWhere(['like', 'collaboration_stage_of_development.id', $this->stage_of_development_id]);
        $query->andFilterWhere(['like', 'collaboration_type.id', $this->collaboration_type_id]);
    }

    /**
     * Filter ONU Goals tags if set
     * @param $query
     * @return void
     */
    public function filterByOnuGoalsTags($query) {
        if (!empty($this->tagValuesGoal)) {
            $query->andWhere(['entitys_tags_mm.tag_id' => $this->tagValuesGoal])
                    ->andWhere(['entitys_tags_mm.classname' => $this->modelClassName]);
        }
    }

    /**
     * Set Cms params
     * @param $dataProvider
     * @param $limit
     * @return void
     */
    public function setCmsParams($dataProvider, $limit) {
        if (!is_null($dataProvider)) {
            if (!empty($params["withPagination"])) {
                $dataProvider->setPagination(['pageSize' => $limit]);
            } else {
                $dataProvider->query->limit($limit);
            }

            if (!empty($params["conditionSearch"])) {
                $commands = explode(";", $params["conditionSearch"]);
                foreach ($commands as $command) {
                    $dataProvider->query->andWhere(eval("return " . $command . ";"));
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function getCreatedAt() {
        return $this->created_at;
    }

    /**
     * @inheritdoc
     */
    public function getCreatorNameSurname() {
        /** @var UserProfile $userProfile */
        $userProfile = UserProfile::find()
                ->andWhere(['user_id' => $this->created_by])
                ->one();

        return ((!empty($userProfile)) ? $userProfile->getNomeCognome() : '');
    }

    /**
     * @inheritdoc
     */
    public function getSearchString() {
        return $this->title;
    }

    /**
     * @inheritdoc
     */
    public function getUpdatedAt() {
        return $this->updated_at;
    }

    /**
     * @inheritdoc
     */
    public function getWrappedObject() {
        return $this->findOne($this->id);
    }
}
