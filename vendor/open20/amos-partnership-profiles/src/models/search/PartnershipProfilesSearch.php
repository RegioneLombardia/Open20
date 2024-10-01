<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\models\search
 * @category   CategoryName
 */

namespace open20\amos\partnershipprofiles\models\search;

use open20\amos\core\interfaces\CmsModelInterface;
use open20\amos\core\record\CmsField;
use open20\amos\cwh\query\CwhActiveQuery;
use open20\amos\notificationmanager\base\NotifyWidget;
use open20\amos\notificationmanager\base\NotifyWidgetDoNothing;
use open20\amos\notificationmanager\models\NotificationChannels;
use open20\amos\partnershipprofiles\models\PartnershipProfiles;
use open20\amos\tag\models\EntitysTagsMm;
use open20\amos\core\interfaces\SearchModelInterface;
use open20\amos\core\record\SearchResult;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\di\Container;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;

/**
 * Class PartnershipProfilesSearch
 * PartnershipProfilesSearch represents the model behind the search form about `open20\amos\partnershipprofiles\models\PartnershipProfiles`.
 * @package open20\amos\partnershipprofiles\models\search
 */
class PartnershipProfilesSearch extends PartnershipProfiles implements SearchModelInterface, CmsModelInterface
{
    /**
     * @var Container $container
     */
    private $container;
    public $categories;

    /**
     * @inheritdoc
     */
    public function __construct(array $config = [])
    {
        $this->container = new Container();
        $this->container->set('notify', new NotifyWidgetDoNothing());
        parent::__construct($config);
    }

    /**
     * @return object
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function getNotifier()
    {
        return $this->container->get('notify');
    }

    /**
     * @param $notifier
     */
    public function setNotifier(NotifyWidget $notifier)
    {
        $this->container->set('notify', $notifier);
    }

    /**
     * @param ActiveQuery $query
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    private function notificationOff($query)
    {
        $notify = $this->getNotifier();
        if ($notify) {
            /** @var \open20\amos\notificationmanager\AmosNotify $notify */
            $notify->notificationOff(\Yii::$app->getUser()->id, $this->partnerProfModule->model('PartnershipProfiles'),
                $query, NotificationChannels::CHANNEL_READ);
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [[
                'status',
                'title',
                'short_description',
                'extended_description',
                'expected_contribution',
                'advantages_innovative_aspects',
                'contact_person',
                'other_prospect_desired_collab',
                'partnership_profile_date',
                'english_title',
                'english_short_description',
                'english_extended_description',
                'other_work_language',
                'other_development_stage',
                'other_intellectual_property',
                'partnership_profile_date_from',
                'partnership_profile_date_to',
                'attrPartnershipProfilesTypesMm',
                'work_language_id',
                'development_stage_id',
                'intellectual_property_id',
                'created_at',
                'updated_at',
                'deleted_at',
                'categories'
            ], 'safe'],
            [[
                'expiration_in_months',
                'willingness_foreign_partners'
            ], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $parentBehaviors = parent::behaviors();

        $behaviors = [];
        // If the parent model PartnershipProfiles is a model enabled for tags, PartnershipProfilesSearch will have TaggableBehavior too
        $moduleTag = \Yii::$app->getModule('tag');
        if (isset($moduleTag) && in_array($this->partnerProfModule->model('PartnershipProfiles'),
                $moduleTag->modelsEnabled) && $moduleTag->behaviors) {
            $behaviors = ArrayHelper::merge($moduleTag->behaviors, $behaviors);
        }

        return ArrayHelper::merge($parentBehaviors, $behaviors);
    }

    /**
     * This is the base search.
     * @param array $params
     * @return ActiveQuery
     */
    public function baseSearch($params)
    {
        /** @var PartnershipProfiles $partnershipProfilesModel */
        $partnershipProfilesModel = $this->partnerProfModule->createModel('PartnershipProfiles');

        /** @var ActiveQuery $query */
        $query = $partnershipProfilesModel::find()->distinct();

        $query->joinWith('partnershipProfilesTypesMms');
        $query->joinWith('workLanguage');
        $query->joinWith('developmentStage');
        $query->joinWith('intellectualProperty');

        // Init the default search values
        $this->initOrderVars();

        // Check params to get orders value
        $this->setOrderVars($params);

        // Add the search by tags query part
        $this->addSearchByTagsQueryPart($query, $params);

        return $query;
    }

    /**
     * @param ActiveQuery $query
     * @param array $params
     * @return ActiveQuery
     */
    protected function addSearchByTagsQueryPart($query, $params)
    {
        $moduleTag = \Yii::$app->getModule('tag');
        $partnershipProfilesClassname = $this->partnerProfModule->model('PartnershipProfiles');
        if (isset($moduleTag) && in_array($partnershipProfilesClassname, $moduleTag->modelsEnabled) && $moduleTag->behaviors) {
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
                                    "entities_tag.classname = '" . addslashes($partnershipProfilesClassname) . "' AND entities_tag.record_id = " . PartnershipProfiles::tableName() . ".id");
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
     * @param ActiveQuery $query
     * @return ActiveQuery
     */
    public function baseFilter($query)
    {
        $query->andFilterWhere(['like', self::tableName() . '.title', $this->title])
            ->andFilterWhere(['like', self::tableName() . '.short_description', $this->short_description]);

        if (!empty($this->categories)) {
            $query
                ->leftJoin('partnership_profiles_category_mm', 'partnership_profiles_category_mm.partnership_profiles_id = partnership_profiles.id')
                ->andWhere(['partnership_profiles_category_mm.partnership_profiles_category_id' => $this->categories]);
        }

        return $query;
    }

    /**
     * @param array $params
     * @return ActiveQuery
     */
    public function searchQuery($params)
    {
        $query = $this->baseSearch($params);
        $classname = $this->partnerProfModule->model('PartnershipProfiles');
        $moduleCwh = \Yii::$app->getModule('cwh');
        $cwhActiveQuery = null;

        if (isset($moduleCwh)) {
            /** @var \open20\amos\cwh\AmosCwh $moduleCwh */
            $moduleCwh->setCwhScopeFromSession();
            $cwhActiveQuery = new CwhActiveQuery(
                $classname, [
                'queryBase' => $query
            ]);
        }
        $isSetCwh = $this->isSetCwh($moduleCwh, $classname);
        if ($isSetCwh) {
            $query = $cwhActiveQuery->getQueryCwhOwnInterest();
        } else {
            $query->andWhere(['status' => [self::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_VALIDATED, self::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_FEEDBACKRECEIVED]]);
        }
        return $query;
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function search($params, $queryType = null, $limit = null, $onlyDrafts = false, $pageSize = null)
    {
        $query = $this->searchQuery($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->notificationOff($query);


        $dataProvider = $this->searchDefaultOrder($dataProvider);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->baseFilter($query);

        return $dataProvider;
    }

    /**
     * @param array $params
     * @return ActiveQuery
     */
    public function searchAllQuery($params)
    {
        $query = $this->baseSearch($params);
        $classname = $this->partnerProfModule->model('PartnershipProfiles');
        $moduleCwh = \Yii::$app->getModule('cwh');
        $cwhActiveQuery = null;
        if (isset($moduleCwh)) {
            /** @var \open20\amos\cwh\AmosCwh $moduleCwh */
            $moduleCwh->setCwhScopeFromSession();
            $cwhActiveQuery = new CwhActiveQuery(
                $classname, [
                'queryBase' => $query
            ]);
        }
        $isSetCwh = $this->isSetCwh($moduleCwh, $classname);
        if ($isSetCwh) {
            $query = $cwhActiveQuery->getQueryCwhAll(null, null, true);
        } else {
            $query->andWhere(['status' => [self::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_VALIDATED, self::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_FEEDBACKRECEIVED]]);
        }

        return $query;
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function searchAll($params, $limit = null)
    {
        $query = $this->searchAllQuery($params);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->notificationOff($query);
        $dataProvider = $this->searchDefaultOrder($dataProvider);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->baseFilter($query);

        return $dataProvider;
    }

    /**
     * @return ActiveQuery
     */
    public function searchAllAdminQuery()
    {
        /** @var PartnershipProfiles $partnershipProfilesModel */
        $partnershipProfilesModel = $this->partnerProfModule->createModel('PartnershipProfiles');

        /** @var ActiveQuery $query */
        $query = $partnershipProfilesModel::find()->distinct();
        $query->joinWith('partnershipProfilesTypesMms');
        $query->joinWith('workLanguage');
        $query->joinWith('developmentStage');
        $query->joinWith('intellectualProperty');
        return $query;
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function searchAllAdmin($params, $limit = null)
    {
        $query = $this->searchAllAdminQuery();

        // Init the default search values
        $this->initOrderVars();

        // Check params to get orders value
        $this->setOrderVars($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->notificationOff($query);
        $dataProvider = $this->searchDefaultOrder($dataProvider);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->baseFilter($query);

        return $dataProvider;
    }

    /**
     * @param array $params
     * @return ActiveQuery
     */
    public function searchCreatedByQuery($params)
    {
        $query = $this->baseSearch($params);
        $classname = $this->partnerProfModule->model('PartnershipProfiles');
        $moduleCwh = \Yii::$app->getModule('cwh');
        $cwhActiveQuery = null;
        if (isset($moduleCwh)) {
            /** @var \open20\amos\cwh\AmosCwh $moduleCwh */
            $moduleCwh->setCwhScopeFromSession();
            $cwhActiveQuery = new CwhActiveQuery(
                $classname, [
                'queryBase' => $query
            ]);
        }
        $isSetCwh = $this->isSetCwh($moduleCwh, $classname);
        if ($isSetCwh) {
            $query = $cwhActiveQuery->getQueryCwhOwn();
        } else {
            $query->andWhere([self::tableName() . '.created_by' => \Yii::$app->user->getId()]);
        }
        return $query;
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function searchCreatedBy($params)
    {
        $query = $this->searchCreatedByQuery($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->notificationOff($query);
        $dataProvider = $this->searchDefaultOrder($dataProvider);


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->baseFilter($query);

        return $dataProvider;
    }

    /**
     * @param array $params
     * @return ActiveQuery
     */
    public function searchToValidateQuery($params)
    {
        $query = $this->baseSearch($params);
        $classname = $this->partnerProfModule->model('PartnershipProfiles');
        $moduleCwh = \Yii::$app->getModule('cwh');
        $cwhActiveQuery = null;

        if (isset($moduleCwh)) {
            /** @var \open20\amos\cwh\AmosCwh $moduleCwh */
            $moduleCwh->setCwhScopeFromSession();
            $cwhActiveQuery = new CwhActiveQuery(
                $classname, [
                'queryBase' => $query
            ]);
        }
        $isSetCwh = $this->isSetCwh($moduleCwh, $classname);
        if ($isSetCwh) {
            $query = $cwhActiveQuery->getQueryCwhToValidate();
        } else {
            $query->andWhere(['status' => self::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_TOVALIDATE]);
        }
        return $query;
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function searchToValidate($params, $limit = null)
    {
        $query = $this->searchToValidateQuery($params);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->notificationOff($query);
        $dataProvider = $this->searchDefaultOrder($dataProvider);


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->baseFilter($query);

        return $dataProvider;
    }

    /**
     * @param array $params
     * @return ActiveQuery
     */
    public function searchArchivedQuery($params)
    {
        $query = $this->baseSearch($params);
        $query->andWhere(['status' => self::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_ARCHIVED]);
        return $query;
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function searchArchived($params)
    {
        $query = $this->searchArchivedQuery($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->notificationOff($query);
        $dataProvider = $this->searchDefaultOrder($dataProvider);


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->baseFilter($query);

        return $dataProvider;
    }

    /**
     * @param array $params
     * @return ActiveQuery
     */
    public function searchClosedQuery($params)
    {
        $query = $this->baseSearch($params);
        $query->andWhere(['status' => self::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_CLOSED]);
        return $query;
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function searchClosed($params)
    {
        $query = $this->searchClosedQuery($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->notificationOff($query);
        $dataProvider = $this->searchDefaultOrder($dataProvider);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->baseFilter($query);

        return $dataProvider;
    }

    /**
     * @param array $params
     * @return ActiveQuery
     */
    public function searchForFacilitatorQuery($params)
    {
        $query = $this->baseSearch($params);
        $query->andWhere(['partnership_profile_facilitator_id' => \Yii::$app->user->id]);
        $query->andWhere(['<>', 'partnership_profiles.created_by', \Yii::$app->user->id]);
        $query->andWhere(['<>', 'status', PartnershipProfiles::PARTNERSHIP_PROFILES_WORKFLOW_STATUS_DRAFT]);
        return $query;
    }

    /**
     * Search for facilitator.
     * @param array $params
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function searchForFacilitator($params)
    {
        $query = $this->searchForFacilitatorQuery($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->notificationOff($query);
        $dataProvider = $this->searchDefaultOrder($dataProvider);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->baseFilter($query);

        return $dataProvider;
    }

    /**
     * @param $moduleCwh
     * @param $classname
     * @return bool
     */
    private function isSetCwh($moduleCwh, $classname)
    {
        if (isset($moduleCwh) && in_array($classname, $moduleCwh->modelsEnabled)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param array $params
     * @param null $limit
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function latestPartenershipProfilesSearch($params, $limit = null)
    {
        $dataProvider = $this->searchAll($params);
        $dataProvider->query->orderBy(['created_at' => SORT_DESC]);
        $dataProvider->pagination->pageSize = $limit;
        return $dataProvider;
    }

    /**
     * Search all validated documents
     *
     * @param array $searchParamsArray Array of search words
     * @param int|null $pageSize
     * @return ActiveDataProvider
     */
    public function globalSearch($searchParamsArray, $pageSize = 5)
    {
        $dataProvider = $this->searchAll([]);
        $pagination = $dataProvider->getPagination();
        if (!$pagination) {
            $pagination = new Pagination();
            $dataProvider->setPagination($pagination);
        }
        $pagination->setPageSize($pageSize);

        // Verifico se il modulo supporta i TAG e, in caso, ricerco anche fra quelli
        $moduleTag = \Yii::$app->getModule('tag');
        $enableTagSearch = isset($moduleTag) && in_array($this->partnerProfModule->model('PartnershipProfiles'),
                $moduleTag->modelsEnabled);

        if ($enableTagSearch) {
            $dataProvider->query->leftJoin('entitys_tags_mm e_tag',
                "e_tag.record_id=" . PartnershipProfiles::tableName() . ".id AND e_tag.deleted_at IS NULL AND e_tag.classname='" . addslashes(PartnershipProfiles::className()) . "'");

//            if (Yii::$app->db->schema->getTableSchema('tag__translation')) {
//                // Esiste la tabella delle traduzioni dei TAG. Uso quella per la ricerca
//                $dataProvider->query->leftJoin('tag__translation tt', "e_tag.tag_id=tt.tag_id");
//                $tagTranslationSearch = true;
//            }

            $dataProvider->query->leftJoin('tag t', "e_tag.tag_id=t.id");
        }


        foreach ($searchParamsArray as $searchString) {
            $orQueries = [
                'or',
                ['like', self::tableName() . '.title', $searchString],
                ['like', self::tableName() . '.short_description', $searchString],
                ['like', self::tableName() . '.extended_description', $searchString],
                ['like', self::tableName() . '.advantages_innovative_aspects', $searchString],
                ['like', self::tableName() . '.other_prospect_desired_collab', $searchString],
                ['like', self::tableName() . '.expected_contribution', $searchString],
                ['like', self::tableName() . '.contact_person', $searchString],
                ['like', self::tableName() . '.english_title', $searchString],
                ['like', self::tableName() . '.english_short_description', $searchString],
                ['like', self::tableName() . '.english_extended_description', $searchString],
            ];

            $tagsValues = \Yii::$app->request->get('tagValues');
            if ($enableTagSearch) {
                $arrayTagIds = [];
                if (!empty($tagsValues)) {
                    $tagIds = ArrayHelper::merge($arrayTagIds, explode(',', $tagsValues));
                    $dataProvider->query->andFilterWhere(['t.id' => $tagIds]);
                }

//                if ($tagTranslationSearch) {
//                    $orQueries[] = ['like', 'tt.nome', $searchString];
//                }
//                $orQueries[] = ['like', 't.nome', $searchString];
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
    public function convertToSearchResult($model)
    {
        $searchResult = new SearchResult();
        $searchResult->url = $model->getFullViewUrl();
        $searchResult->box_type = "none";
        $searchResult->id = $model->id;
        $searchResult->titolo = $model->title;
        $searchResult->data_pubblicazione = $model->partnership_profile_date;
        $searchResult->abstract = $model->short_description;
        return $searchResult;
    }


    /**
     * Search method useful to retrieve news to show in frontend (with cms)
     *
     * @param $params
     * @param int|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearch($params, $limit = null)
    {
        $params = array_merge($params, \Yii::$app->request->get());
        $this->load($params);
        $query = $this->searchAllQuery($params);
        //$this->applySearchFilters($query);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'key' => 'id',
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);

        if (!empty($params["withPagination"])) {
            $dataProvider->setPagination(['pageSize' => $limit]);
            $query->limit(null);
        } else {
            $query->limit($limit);
        }

        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                if (strpos($command, 'partnership_profiles_categories_ids') !== false) {
                    $this->cmsFilterCategories($command, $query);
                } else {
                    $query->andWhere(eval("return " . $command . ";"));
                }
            }
        }

        return $dataProvider;
    }

    /**
     * @param $command
     * @param $query ActiveQuery
     */
    public function cmsFilterCategories($command, $query){
        $explode = explode('=>',$command );
        if(count($explode) == 2){
            $val = trim($explode[1]);
            $val = str_replace('[', '', $val);
            $val = str_replace(']', '', $val);
            $categoryIds = explode(',', $val);
            $query->leftJoin('partnership_profiles_category_mm', 'partnership_profiles_category_mm.partnership_profiles_id = partnership_profiles.id')
                ->andFilterWhere(['partnership_profiles_category_mm.partnership_profiles_category_id' => $categoryIds]);
        }
    }

    /**
     * Search method useful to retrieve news to show in frontend (with cms)
     *
     * @param $params
     * @param int|null $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchOwnInterest($params, $limit = null)
    {
        $params = array_merge($params, \Yii::$app->request->get());
        $this->load($params);
        $query = $this->searchQuery($params);
        //$this->applySearchFilters($query);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'key' => 'id',
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);

        if (!empty($params["withPagination"])) {
            $dataProvider->setPagination(['pageSize' => $limit]);
            $query->limit(null);
        } else {
            $query->limit($limit);
        }

        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                $query->andWhere(eval("return " . $command . ";"));
            }
        }

        return $dataProvider;
    }

    /**
     *
     * @return array
     */
    public function cmsViewFields()
    {
        $viewFields = [];

//    array_push($viewFields, new CmsField("titolo", "TEXT", 'amosnews', $this->attributeLabels()["titolo"]));
//    array_push($viewFields, new CmsField("descrizione_breve", "TEXT", 'amosnews', $this->attributeLabels()['descrizione_breve']));
//    array_push($viewFields, new CmsField("newsImage", "IMAGE", 'amosnews', $this->attributeLabels()['newsImage']));
//    array_push($viewFields, new CmsField("data_pubblicazione", "DATE", 'amosnews', $this->attributeLabels()['data_pubblicazione']));

        $viewFields[] = new CmsField("title", "TEXT", 'amospartnershipprofiles', $this->attributeLabels()["title"]);
        $viewFields[] = new CmsField("short_description", "TEXT", 'amospartnershipprofiles', $this->attributeLabels()['short_description']);
//        $viewFields[] = new CmsField("data_pubblicazione", "DATE", 'amospartnershipprofiles',
//            $this->attributeLabels()['data_pubblicazione']);

        return $viewFields;
    }

    /**
     *
     * @return array
     */
    public function cmsSearchFields()
    {
        $searchFields = [];
        $searchFields[] = new CmsField("title", "TEXT");
        $searchFields[] = new CmsField("short_description", "TEXT");;

        return $searchFields;
    }

    /**
     *
     * @param int $id
     * @return boolean
     */
    public function cmsIsVisible($id)
    {
        $retValue = true;
        return $retValue;
    }


}