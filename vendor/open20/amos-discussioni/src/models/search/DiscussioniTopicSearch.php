<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni\models\search
 * @category   CategoryName
 */

namespace open20\amos\discussioni\models\search;

use open20\amos\community\models\Community;
use open20\amos\core\interfaces\ContentModelSearchInterface;
use open20\amos\core\interfaces\SearchModelInterface;
use open20\amos\core\interfaces\CmsModelInterface;
use open20\amos\core\module\AmosModule;
use open20\amos\core\record\SearchResult;
use open20\amos\core\record\CmsField;
use open20\amos\cwh\models\CwhConfig;
use open20\amos\discussioni\models\DiscussioniTopic;
use open20\amos\news\models\News;
use open20\amos\notificationmanager\base\NotifyWidget;
use open20\amos\notificationmanager\models\NotificationChannels;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\ActiveQuery;
use yii\di\Container;
use yii\helpers\ArrayHelper;
use open20\amos\discussioni\AmosDiscussioni;

/**
 * Class DiscussioniTopicSearch
 * DiscussioniTopicSearch represents the model behind the search form about `open20\amos\discussioni\models\DiscussioniTopic`.
 * @package open20\amos\discussioni\models\search
 */
class DiscussioniTopicSearch extends DiscussioniTopic implements SearchModelInterface, ContentModelSearchInterface, CmsModelInterface
{
    private $container;

    public function __construct(array $config = [])
    {
        $this->isSearch       = true;
        $this->container      = new Container();
        $this->container->set('notify', Yii::$app->getModule('notify'));
        parent::__construct($config);
        $this->modelClassName = AmosDiscussioni::instance()->model('DiscussioniTopic');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by', 'deleted_by', 'version'], 'integer'],
            [['titolo', 'testo', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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
        //if the parent model News is a model enabled for tags, NewsSearch will have TaggableBehavior too
        $moduleTag = \Yii::$app->getModule('tag');
        if (isset($moduleTag) && in_array($this->modelClassName, $moduleTag->modelsEnabled) && $moduleTag->behaviors) {
            $behaviors = ArrayHelper::merge($moduleTag->behaviors, $behaviors);
        }

        return ArrayHelper::merge($parentBehaviors, $behaviors);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function baseSearch($params)
    {
        //init the default search values
        $this->initOrderVars();

        //check params to get orders value
        $this->setOrderVars($params);

        $query = DiscussioniTopic::find()->distinct();
        return $query;
    }

    /**
     * @param array $params
     * @param string $queryType
     * @param int|null $limit
     * @return ActiveDataProvider
     */
    public function search($params, $queryType = null, $limit = null, $onlyDrafts = false, $pageSize = null)
    {
        $query  = $this->buildQuery($queryType, $params);
        $query->limit($limit);
        //Switch off notification service for not readed discussion notifications
        $notify = $this->getNotifier();
        if ($notify) {
            /** @var \open20\amos\notificationmanager\AmosNotify
              $notify */
            $this->getNotifier();
            $notify->notificationOff(Yii::$app->getUser()->id, $this->modelClassName, $query,
                NotificationChannels::CHANNEL_READ);
        }
        $dp_params = ['query' => $query,];
        if ($limit) {
            $dp_params ['pagination'] = false;
        }
        //set the data provider
        $dataProvider = new ActiveDataProvider($dp_params);

        $dataProvider = $this->searchDefaultOrder($dataProvider);

        /** filter by selected tag values (OR condition)  */
        if (isset($params[$this->formName()]['tagValues'])) {
            $tagValues = $params[$this->formName()]['tagValues'];
            $this->setTagValues($tagValues);
            if (is_array($tagValues) && !empty($tagValues)) {
                $andWhere = "";
                $i        = 0;
                foreach ($tagValues as $rootId => $tagId) {
                    if (!empty($tagId)) {
                        if ($i == 0) {
                            $query->innerJoin('entitys_tags_mm entities_tag',
                                "entities_tag.classname = '".
                                addslashes($this->modelClassName)."' AND entities_tag.record_id=discussioni_topic.id");
                        } else {
                            $andWhere .= " OR ";
                        }
                        $andWhere .= "(entities_tag.tag_id in (".
                            $tagId.") AND entities_tag.root_id = ".$rootId." AND entities_tag.deleted_at is null)";
                        $i++;
                    }
                }
                $andWhere .= "";
                if (!empty($andWhere)) {
                    $query->andWhere($andWhere);
                }
            }
        }

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'discussioni_topic.created_at' => $this->created_at,
            'discussioni_topic.updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'discussioni_topic.created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_by' => $this->deleted_by,
            'version' => $this->version,
        ]);

        $query->andFilterWhere(['like', 'titolo', $this->titolo])
            ->andFilterWhere(['like', 'testo', $this->testo]);

        return $dataProvider;
    }

    /**
     * @param string $queryType
     * @param array $params
     * @return ActiveQuery $query
     */
    public function buildQuery($queryType, $params, $onlyDrafts = false)
    {
        $query = $this->baseSearch($params);

        $classname      = $this->modelClassName;
        $moduleCwh      = \Yii::$app->getModule('cwh');
        $cwhActiveQuery = null;

        $isSetCwh = $this->isSetCwh($moduleCwh, $classname);
        if ($isSetCwh) {
            $moduleCwh->setCwhScopeFromSession();
            $cwhActiveQuery = new \open20\amos\cwh\query\CwhActiveQuery(
                $classname, [
                'queryBase' => $query
            ]);
        }

        switch ($queryType) {
            case 'created-by':
                if ($isSetCwh) {
                    $query = $cwhActiveQuery->getQueryCwhOwn();
                } else {
                    $query->andFilterWhere([
                        'created_by' => Yii::$app->getUser()->id
                    ]);
                }
                break;
            case 'all':
                if ($isSetCwh) {
                    $query = $cwhActiveQuery->getQueryCwhAll(null,null,true);
                } else {
                    $query->andWhere([
                        'status' => DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_ATTIVA
                    ]);
                }
                break;
            case'to-validate':
                if ($isSetCwh) {
                    $query = $cwhActiveQuery->getQueryCwhToValidate();
                } else {
                    $query->andWhere([
                        'status' => DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_DAVALIDARE
                    ]);
                }
                break;
            case 'own-interest':
                if ($isSetCwh) {
                    $query = $cwhActiveQuery->getQueryCwhOwnInterest();
                } else {
                    $query->andWhere([
                        'status' => DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_ATTIVA
                    ]);
                }
                break;
            case 'admin-all':
                /* no filter */
                break;
        }

        return $query;
    }

    /**
     * @param AmosModule $moduleCwh
     * @param string $classname
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
     * Search discussions created by logged user
     * @param $params
     * @param null $limit
     * @return ActiveDataProvider
     */
    public function searchCreatedBy($params, $limit = null)
    {
        return $this->search($params, 'created-by', $limit);
    }

    /**
     * Search discussions in 'to validate' that logged user has permission to validate
     * @param $params
     * @param int|null $limit
     * @return ActiveDataProvider
     */
    public function searchToValidate($params, $limit = null)
    {
        return $this->search($params, 'to-validate', $limit);
    }

    /**
     * Search all discussions in status validated
     * @param $params
     * @param null $limit
     * @return ActiveDataProvider
     */
    public function searchAll($params, $limit = null)
    {
        $dataProvider = $this->search($params, 'all', $limit);
        if (Yii::$app->user->isGuest) {
            $dataProvider->query->andWhere(['primo_piano' => true]);
        }

        return $dataProvider;
    }

    /**
     * @param $params
     * @param null $limit
     * @return ActiveDataProvider
     */
    public function searchAdminAll($params, $limit = null)
    {
        return $this->search($params, 'admin-all', $limit);
    }

    /**
     * Search discussion in status validated matching logged user parameters (based on publication rule)
     * @param $params
     * @param null $limit
     * @return ActiveDataProvider
     */
    public function searchOwnInterest($params, $limit = null)
    {
        return $this->search($params, 'own-interest', $limit);
    }

    /**
     * @param $params
     * @param null $limit
     * @return ActiveDataProvider
     */
    public function ultimeDiscussioni($params, $limit = null)
    {
        // solo le discussioni attive
        $dataProvider = $this->searchAll($params, $limit);

        return $dataProvider;
    }

    /**
     * @param $params
     * @param null $limit
     * @return ActiveDataProvider
     */
    public function discussioniInEvidenza($params, $limit = null)
    {
        $query        = $this->searchAll($params, $limit)->query;
        $query->andFilterWhere([
            'in_evidenza' => true
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
            'pagination' => [
                'pageSize' => $limit,
            ]
        ]);

        return $dataProvider;
    }

    /**
     * @param $notifier
     */
    public function setNotifier(NotifyWidget $notifier)
    {
        $this->container->set('notify', $notifier);
    }

    /**
     * @return $this
     */
    public function getNotifier()
    {
        return $this->container->get('notify');
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
        $dataProvider = $this->search([], 'all', null);
        $pagination   = $dataProvider->getPagination();
        if (!$pagination) {
            $pagination = new Pagination();
            $dataProvider->setPagination($pagination);
        }
        $pagination->setPageSize($pageSize);

        // Verifico se il modulo supporta i TAG e, in caso, ricerco anche fra quelli
        $moduleTag       = \Yii::$app->getModule('tag');
        $enableTagSearch = isset($moduleTag) && in_array($this->modelClassName, $moduleTag->modelsEnabled);

        if ($enableTagSearch) {
            $dataProvider->query->leftJoin('entitys_tags_mm e_tag',
                "e_tag.record_id=discussioni_topic.id AND e_tag.deleted_at IS NULL AND e_tag.classname='".addslashes($this->modelClassName)."'");

//            if (Yii::$app->db->schema->getTableSchema('tag__translation')) {
//                // Esiste la tabella delle traduzioni dei TAG. Uso quella per la ricerca
//                $dataProvider->query->leftJoin('tag__translation tt', "e_tag.tag_id=tt.tag_id");
//                $tagTranslationSearch = true;
//            }

            $dataProvider->query->leftJoin('tag t', "e_tag.tag_id=t.id");
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
        }


        foreach ($searchParamsArray as $searchString) {
            $orQueries = [
                'or',
                ['like', 'discussioni_topic.titolo', $searchString],
                ['like', 'discussioni_topic.testo', $searchString],
            ];

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
        $searchResult                     = new SearchResult();
        $searchResult->url                = $model->getFullViewUrl();
        $searchResult->box_type           = "image";
        $searchResult->id                 = $model->id;
        $searchResult->titolo             = $model->titolo;
        $searchResult->data_pubblicazione = $model->created_at;
        $searchResult->immagine           = $model->discussionsTopicImage;
        $searchResult->abstract           = $model->testo;
        return $searchResult;
    }

    /**
     * Search method useful to retrieve discussions to show in frontend (with cms)
     *
     * @param $params
     * @param int|null $limit

     * @return ActiveDataProvider
     */
    public function cmsSearch($params, $limit = null)
    {
        $this->load($params);
        $query        = $this->homepageDiscussioniQuery($params);
        $this->applySearchFilters($query);
        $query->limit($limit);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);
        return $dataProvider;
    }

    /**
     * @param $params
     * @param $limit
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function cmsSearchCommunity($params, $limit = null){
        $this->load($params);
        $query        = $this->homepageDiscussioniCommunityQuery($params);
        $this->applySearchFilters($query);
        $query->limit($limit);

        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                if (strpos($command, 'scope_community_id')) {
                    $communityId = $this->extractCommunityIdFromCommand($command);
                    $query = $this->cmsFilterScopeCommunity($query, $communityId);
                } else {
                    $query->andWhere(eval("return " . $command . ";"));
                }
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);
        return $dataProvider;
    }



    /**
     * @param $command
     * @return string|null
     */
    public function extractCommunityIdFromCommand($command)
    {
        $community_id = null;
        $explode = explode('=>', $command);
        if (count($explode) == 2) {
            $community_id = trim($explode[1]);
        }
        return $community_id;
    }

    /**
     * @param $query
     * @param $community_id
     * @return mixed|\yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function cmsFilterScopeCommunity($query, $community_id)
    {
        if ($community_id) {
            $cwhConfig = CwhConfig::find()->andWhere(['tablename' => 'community'])->one();
            if ($cwhConfig) {
                $cwhActiveQuery = new \open20\amos\cwh\query\CwhActiveQuery(
                    DiscussioniTopic::className(),
                    [
                        'queryBase' => $query,
                        'bypassScope' => false
                    ]
                );
                $query = $cwhActiveQuery->getQueryCwhAll($cwhConfig->id, $community_id, false);
            }
        }
        return $query;
    }



    /**
     *
     * @param type $params
     * @param type $limit
     * @return type
     */
    public function cmsSearchDiscussioniProgrammaStrategico($params, $limit = null)
    {

        $querycwhpubb = "
			SELECT
				`content_id`
			FROM `cwh_pubblicazioni` cp
                        JOIN `cwh_config_contents` ccc
                        ON cp.`cwh_config_contents_id` = ccc.`id`
			AND ccc.`tablename` = '".DiscussioniTopic::tableName()."'
                        WHERE cp.`id` in (
                            SELECT a.`cwh_pubblicazioni_id`
                            FROM `cwh_pubblicazioni_cwh_nodi_editori_mm` a
                            JOIN `cwh_config` b
                            on a.`cwh_config_id` = b.`id` and b.`tablename` = '".Community::tableName()."'
                            where a.`cwh_network_id` in (2604,1425,2602,2608))
        ";

        $paramsId = \Yii::$app->getDb()->createCommand($querycwhpubb)->queryAll();
        $ids      = [];
        foreach ($paramsId as $param) {
            $ids[] = $param['content_id'];
        }
        $this->load($params);
        $query        = $this->homepageDiscussioniProgrammaStrategicoQuery($params, $ids);
        $this->applySearchFilters($query);
        $query->limit($limit);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);

        return $dataProvider;
    }

    public function cmsViewFields()
    {
        $viewFields = [];

        array_push($viewFields, new CmsField("titolo", "TEXT", 'amosdiscussioni', $this->attributeLabels()['titolo']));
        array_push($viewFields, new CmsField("testo", "TEXT", 'amosdiscussioni', $this->attributeLabels()['testo']));
        array_push($viewFields,
            new CmsField("discussionsTopicImage", "IMAGE", 'amosdiscussioni',
            $this->attributeLabels()['discussionsTopicImage']));


        return $viewFields;
    }

    public function cmsSearchFields()
    {
        $searchFields = [];

        array_push($searchFields, new CmsField("titolo", "TEXT"));
        array_push($searchFields, new CmsField("testo", "TEXT"));

        return $searchFields;
    }

    /**
     *
     * @param type $id
     * @return boolean
     */
    public function cmsIsVisible($id)
    {
        $retValue = true;
        return $retValue;
    }

    /**
     * @inheritdoc
     */
    public function searchDefaultOrder($dataProvider)
    {
        // Check if can use the custom module order
        if ($this->canUseModuleOrder()) {
            $dataProvider->setSort($this->createOrderClause());
        } else { // For widget graphic last news, order is incorrect without this else
            $dataProvider->setSort([
                'defaultOrder' => [
                    'created_at' => SORT_DESC
                ]
            ]);
        }
        return $dataProvider;
    }

    /**
     * @inheritdoc
     */
    public function searchOwnInterestsQuery($params)
    {
        return $this->buildQuery('own-interest', $params);
    }

    /**
     * @inheritdoc
     */
    public function searchAllQuery($params)
    {
        return $this->buildQuery('all', $params);
    }

    /**
     * @inheritdoc
     */
    public function searchToValidateQuery($params)
    {
        return $this->buildQuery('to-validate', $params);
    }

    /**
     * @inheritdoc
     */
    public function searchCreatedByMeQuery($params)
    {
        return $this->buildQuery('created-by', $params);
    }

    /**
     *
     * @param type $params
     * @return type
     */
    public function homepageDiscussioniQuery($params)
    {
        $tableName = $this->tableName();
        $query     = $this->baseSearch($params)
            ->andWhere([
            $tableName.'.status' => DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_ATTIVA,
        ]);
        if (\Yii::$app->user->isGuest) {
            $query->andWhere($tableName.'.primo_piano = 1');
        }else{
            $query = $this->searchAllQuery($params);
        }

        return $query;
    }

    /**
     * @param $params
     * @return ActiveQuery
     */
    public function homepageDiscussioniCommunityQuery($params)
    {
        $tableName = $this->tableName();
        $query     = $this->baseSearch($params)
            ->andWhere([
                $tableName.'.status' => DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_ATTIVA,
            ]);
        $query->andWhere($tableName.'.primo_piano = 1');

        return $query;
    }

    /**
     *
     * @param type $params
     * @return type
     */
    public function homepageDiscussioniProgrammaStrategicoQuery($params, $paramsId)
    {
        $tableName = $this->tableName();
        $query     = $this->baseSearch($params)
                ->andWhere([
                    $tableName.'.status' => DiscussioniTopic::DISCUSSIONI_WORKFLOW_STATUS_ATTIVA,
                ])->andFilterWhere(['in', 'id', $paramsId]);
        //->andWhere($tableName . '.primo_piano = 1');
        return $query;
    }
}
