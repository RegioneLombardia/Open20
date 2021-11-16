<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events
 * @category   CategoryName
 */

namespace open20\amos\events\models\search;

use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\interfaces\CmsModelInterface;
use open20\amos\core\interfaces\SearchModelInterface;
use open20\amos\core\record\CmsField;
use open20\amos\core\record\SearchResult;
use open20\amos\cwh\AmosCwh;
use open20\amos\cwh\query\CwhActiveQuery;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventLanding;
use open20\amos\events\models\EventMembershipType;
use open20\amos\events\models\EventType;
use open20\amos\events\utility\EventsUtility;
use open20\amos\news\models\search\NewsSearch;
use open20\amos\notificationmanager\AmosNotify;
use open20\amos\notificationmanager\base\NotifyWidget;
use open20\amos\notificationmanager\base\NotifyWidgetDoNothing;
use open20\amos\notificationmanager\models\NotificationChannels;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\di\Container;
use yii\di\NotInstantiableException;
use yii\helpers\ArrayHelper;

/**
 * Class EventSearch
 * EventSearch represents the model behind the search form about `open20\amos\events\models\Event`.
 * @package open20\amos\events\models\search
 */
class EventSearch extends Event implements SearchModelInterface, CmsModelInterface
{
    public $showHistory = 0;
    public $tagPreference;

    /**
     * @var Container $container
     */
    private $container;

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
     * @throws InvalidConfigException
     * @throws NotInstantiableException
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
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    private function notificationOff($query)
    {
        $notify = $this->getNotifier();

        if ($notify) {
            /** @var AmosNotify $notify */
            $notify->notificationOff(
                \Yii::$app->getUser()->id, $this->eventsModule->model('Event'),
                $query, NotificationChannels::CHANNEL_READ
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'primo_piano', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [
                [
                    'title',
                    'description',
                    'begin_date_hour',
                    'end_date_hour',
                    'event_type_id',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                    'showHistory',
                    'tagPreference'
                ],
                'safe'
            ],
            [
                [
                    'begin_date_hour_from',
                    'begin_date_hour_to',
                    'end_date_hour_from',
                    'end_date_hour_to',
                ],
                'safe'
            ],
        ];
    }

    /**
     * bypass scenarios() implementation in the parent class
     *
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     */
    public function behaviors()
    {
        $parentBehaviors = parent::behaviors();

        $behaviors = [];
        //if the parent model News is a model enabled for tags, NewsSearch will have TaggableBehavior too
        $moduleTag = \Yii::$app->getModule('tag');
        if (isset($moduleTag) && in_array(
                $this->eventsModule->model('Event'), $moduleTag->modelsEnabled
            ) && $moduleTag->behaviors) {
            $behaviors = ArrayHelper::merge($moduleTag->behaviors, $behaviors);
        }

        return ArrayHelper::merge($parentBehaviors, $behaviors);
    }

    /**
     * @param $params
     * @return ActiveQuery $query
     */
    public function baseSearch($params)
    {
        //init the default search values
        $this->initOrderVars();

        if (is_string($params)) {
            $params = [$params];
        }

        //check params to get orders value
        $this->setOrderVars($params);

        $module = AmosEvents::instance();
        /** @var Event $eventModel */
        $eventModel = $module->model('Event');

        return $eventModel::find()->distinct();
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params, $queryType = null, $limit = null,
                           $onlyDrafts = false)
    {
        $query = $this->buildQuery($params, $queryType, $onlyDrafts);

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
            ]
        );

//        $dataProvider = $this->searchDefaultOrder($dataProvider);
        $dataProvider->setSort([
            'attributes' => ['begin_date_hour', 'title', 'event_type_id', 'status'],
            'defaultOrder' => [
                'begin_date_hour' => SORT_ASC
            ]
        ]);

        if (!empty($params["withPagination"])) {
            $dataProvider->setPagination(['pageSize' => $limit]);
            $query->limit(null);
        } else {
            $query->limit($limit);
        }
        $this->notificationOff($query);

        $query->joinWith('eventType');


        $loaded = $this->load($params);
        // show history or not
//        if(!in_array($queryType,['all-admin','my-group'])){
        if (!$this->showHistory) {
            $query->andWhere(['>', 'event.end_date_hour', new Expression('NOW()')]);
        }

//        }

        if (!($loaded) && $this->validate()) {
            return $dataProvider;
        }


        if ($this->tagPreference) {
            $query->leftJoin("entitys_tags_mm as tag_preference", 'tag_preference.record_id = event.id')
                ->andWhere(['tag_preference.classname' => Event::className()])
                ->andFilterWhere(['tag_preference.tag_id' => $this->tagPreference]);
        }

        $query
            ->andFilterWhere(
                [
                    self::tableName() . '.id' => $this->id,
                    self::tableName() . '.event_type_id' => $this->event_type_id,
                    self::tableName() . '.created_at' => $this->created_at,
                    self::tableName() . '.updated_at' => $this->updated_at,
                    self::tableName() . '.deleted_at' => $this->deleted_at,
                    self::tableName() . '.created_by' => $this->created_by,
                    self::tableName() . '.updated_by' => $this->updated_by,
                    self::tableName() . '.deleted_by' => $this->deleted_by,
                ]
            )
            ->andFilterWhere(['like', self::tableName() . '.title', $this->title])
            ->andFilterWhere(['like', self::tableName() . '.description', $this->description])
            ->andFilterWhere(['>=', self::tableName() . '.begin_date_hour', $this->begin_date_hour])
            ->andFilterWhere(['<=', self::tableName() . '.end_date_hour', $this->end_date_hour]);

        return $dataProvider;
    }

    /**
     * Search for events created by the logged user
     *
     * @param array $params $_GET search parametrs
     * @param int|null $limit Query limit
     * @return ActiveDataProvider
     */
    public function searchCreatedBy($params, $limit = null)
    {
        return $this->search($params, 'created-by', $limit);
    }

    /**
     * @param ActiveQuery $query
     */
    private function filterByMembershipType($query)
    {
        $loggedUserId = Yii::$app->getUser()->id;
        $query
            ->leftJoin(
                'community_user_mm',
                'community_user_mm.community_id = event.community_id AND community_user_mm.user_id=' . $loggedUserId
            )
            ->andWhere(['OR',
                ['!=', 'event_type.event_type', EventType::TYPE_UPON_INVITATION],
                ['AND', ['event_type.event_type' => EventType::TYPE_UPON_INVITATION], ['community_user_mm.user_id' => $loggedUserId], ['community_user_mm.deleted_at' => null]]
            ]);
//        pr($query->createCommand()->rawSql);
    }

    /**
     * Search for all events
     *
     * @param array $params $_GET search parametrs
     * @param int|null $limit Query limit
     * @return ActiveDataProvider
     */
    public function searchAllEvents($params = [], $limit = null)
    {
        return $this->search($params, 'all', $limit);
    }

    /**
     * Search for all events
     *
     * @param array $params $_GET search parametrs
     * @param int|null $limit Query limit
     * @return ActiveDataProvider
     */
    public function searchAllAdmin($params, $limit = null)
    {
        return $this->search($params, 'all-admin', $limit);
    }

    /**
     * Search for events visible by the logged user and published on the calendar
     *
     * @param array $params $_GET search parametrs
     * @param int|null $limit Query limit
     * @return ActiveDataProvider
     */
    public function searchCalendarView($params, $limit = null)
    {
        return $this->search($params, 'own-interest', $limit);
    }

    /**
     * Search for events that by the logged user has permission to validate
     *
     * @param array $params $_GET search parametrs
     * @param int|null $limit Query limit
     * @return ActiveDataProvider
     */
    public function searchToPublish($params, $limit = null)
    {
        return $this->search($params, 'to-validate', $limit);
    }

    /**
     * Search for events where the the logged user is part of the staff
     *
     * @param array $params $_GET search parametrs
     * @param int|null $limit Query limit
     * @return ActiveDataProvider
     */
    public function searchManagement($params, $limit = null)
    {
        return $this->search($params, 'all', $limit);
    }

    /**
     * @param $params
     * @param null $limit
     * @return ActiveDataProvider
     */
    public function searchMyGroup($params, $limit = null)
    {
        return $this->search($params, 'my-group', $limit);
    }

    /**
     * @param $params
     * @param null $limit
     * @return ActiveDataProvider
     */
    public function searchMyInvitations($params, $limit = null)
    {
        return $this->search($params, 'my-invitations', $limit);
    }

    /**
     * @param $params
     * @param null $limit
     * @return ActiveDataProvider
     */
    public function searchMyRegistrations($params, $limit = null)
    {
        return $this->search($params, 'my-registrations', $limit);
    }


    /**
     * @param string $queryType
     * @param array $params
     * @return ActiveQuery $query
     */
    public function buildQuery($params, $queryType, $onlyDrafts = false)
    {
        $moduleEvents = \Yii::$app->getModule('events');
        $query = $this->baseSearch($params);
        $classname = $this->eventsModule->model('Event');

        /** @var  AmosCwh $moduleCwh */
        $moduleCwh = \Yii::$app->getModule('cwh');
        $cwhActiveQuery = null;

        $isSetCwh = isset($moduleCwh) && in_array($classname,
                $moduleCwh->modelsEnabled);
        if ($isSetCwh) {
            $moduleCwh->setCwhScopeFromSession();
            $cwhActiveQuery = new CwhActiveQuery(
                $classname,
                [
                    'queryBase' => $query
                ]
            );
        }

        switch ($queryType) {
            case 'my-group':
                $group = EventsUtility::getCurrentDg();
                if ($group) {
                    $query->innerJoin('event_group_referent', 'event_group_referent.id = event.event_group_referent_id')
                        ->andWhere(['is', 'event_group_referent.deleted_at', null])
                        ->andWhere(['event_group_referent.id' => $group->id]);
                } else {
                    $query->andWhere(0);
                }
                break;
            case 'my-registrations':
                $query->innerJoin('community_user_mm', 'community_user_mm.community_id = event.community_id')
                    ->andWhere(['community_user_mm.status' => CommunityUserMm::STATUS_ACTIVE])
                    ->andWhere(['is', 'community_user_mm.deleted_at', null])
                    ->andWhere(['community_user_mm.user_id' => \Yii::$app->user->id]);
                break;
            case 'my-invitations':
                $query2 = clone $query;
                $query2
                    ->select('event.id')
                    ->innerJoin('community_user_mm', 'community_user_mm.community_id = event.community_id')
                    ->andWhere(['community_user_mm.user_id' => \Yii::$app->user->id])
                    ->andWhere(['is', 'community_user_mm.deleted_at', null])
                    ->andWhere(['community_user_mm.status' => CommunityUserMm::STATUS_ACTIVE]);
//                pr($query2->createCommand()->rawSql);
//                pr($query->createCommand()->rawSql);die;

                $query->innerJoinWith('eventInternalLists.eventInvitationSent')
                    ->andWhere(['event_invitation_sent.user_id' => \Yii::$app->user->id])
                    ->andWhere(['is', 'event_invitation_sent.deleted_at', null])
                    ->andWhere(['not in', 'event.id', $query2]);
//                pr($query->createCommand()->rawSql);die;
                break;
            case 'created-by':
                if ($isSetCwh) {
                    $query = $cwhActiveQuery->getQueryCwhOwn();
                } else {
                    $query->andFilterWhere(
                        [
                            self::tableName() . '.created_by' => Yii::$app->getUser()->id
                        ]
                    );
                }
                break;
            case 'all':
                if ($moduleEvents && $moduleEvents->enableNewWizard) {
                    $query->andWhere(
                        [
                            self::tableName() . '.status' => Event::EVENTS_WORKFLOW_STATUS_PUBLISHED,
                        ]
                    );
                    $this->filterByMembershipType($query);
                } else if ($isSetCwh) {
                    $query = $cwhActiveQuery->getQueryCwhAll();
                } else {
                    $query->andWhere(
                        [
                            self::tableName() . '.status' => Event::EVENTS_WORKFLOW_STATUS_PUBLISHED,
                        ]
                    );
                }
                break;
            case'to-validate':
                if ($isSetCwh) {
                    $query = $cwhActiveQuery->getQueryCwhToValidate();
                    $this->filterByMembershipType($query);
                }
                $query->andWhere(
                    [
                        self::tableName() . '.status' => Event::EVENTS_WORKFLOW_STATUS_PUBLISHREQUEST,
                    ]
                );
                break;
            case'all-admin':
                // show all
                break;
            case 'own-interest':
                if ($isSetCwh) {
                    $query = $cwhActiveQuery->getQueryCwhOwnInterest();
                    $this->filterByMembershipType($query);
                    $query->andFilterWhere(
                        [
                            'validated_at_least_once' => true,
                            'publish_in_the_calendar' => true,
                            'visible_in_the_calendar' => true
                        ]
                    );
                } else {
                    $query->andWhere(
                        [
                            self::tableName() . '.status' => Event::EVENTS_WORKFLOW_STATUS_PUBLISHED,
                        ]
                    );
                }
                break;
        }
        if ($onlyDrafts) {
            $query->joinWith('communityUserMm');

            // MANAGEMENT
            $query->andWhere(
                [
                    'community_user_mm.user_id' => \Yii::$app->getUser()->id,
                    'community_user_mm.status' => CommunityUserMm::STATUS_ACTIVE,
                    'community_user_mm.role' => self::EVENT_MANAGER,
                    'validated_at_least_once' => true,
                    'event_management' => true,
                ]
            );
        }

//        pr($query->createCommand()->rawSql);
        return $query;
    }

    /**
     * Search method useful to retrieve events in validated status with both flags
     * publish_in_the_calendar and visible_in_the_calendar true
     *
     * @param array $params Array di parametri
     * @return ActiveDataProvider
     */
    public function searchHighlightedAndHomepageEvents($params)
    {
        $query = $this->highlightedAndHomepageEventsQuery($params);

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'sort' => [
                    'defaultOrder' => [
                        'publication_date_begin' => SORT_DESC,
                    ],
                ],
            ]
        );

        return $dataProvider;
    }

    /**
     * @param array $params
     * @return ActiveQuery
     */
    public function highlightedAndHomepageEventsQuery($params)
    {
        $tableName = $this->tableName();

        return $this->baseSearch($params)
            ->where([])
            ->andWhere(
                [
                    $tableName . '.status' => Event::EVENTS_WORKFLOW_STATUS_PUBLISHED,
                    $tableName . '.in_evidenza' => 1,
                    $tableName . '.primo_piano' => 1,
                    $tableName . '.deleted_at' => null,
                    $tableName . '.publish_in_the_calendar' => 1,
                    $tableName . '.visible_in_the_calendar' => 1
                ]
            );
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
        $pagination = $dataProvider->getPagination();
        if (!$pagination) {
            $pagination = new Pagination();
            $dataProvider->setPagination($pagination);
        }
        $pagination->setPageSize($pageSize);

        // Verifico se il modulo supporta i TAG e, in caso, ricerco anche fra quelli
        $moduleTag = \Yii::$app->getModule('tag');
        $enableTagSearch = isset($moduleTag) && in_array(
                $this->eventsModule->model('Event'), $moduleTag->modelsEnabled
            );

        if ($enableTagSearch) {
            $dataProvider->query->leftJoin(
                'entitys_tags_mm e_tag',
                "e_tag.record_id=event.id AND e_tag.deleted_at IS NULL AND e_tag.classname='" . addslashes(
                    $this->eventsModule->model('Event')
                ) . "'"
            );

            if (Yii::$app->db->schema->getTableSchema('tag__translation')) {
                // Esiste la tabella delle traduzioni dei TAG. Uso quella per la ricerca
                $dataProvider->query->leftJoin('tag__translation tt',
                    "e_tag.tag_id=tt.tag_id");
                $tagTranslationSearch = true;
            }

            $dataProvider->query->leftJoin('tag t', "e_tag.tag_id=t.id");
        }

        foreach ($searchParamsArray as $searchString) {
            $orQueries = [
                'or',
                ['like', self::tableName() . '.title', $searchString],
                ['like', self::tableName() . '.summary', $searchString],
                ['like', self::tableName() . '.description', $searchString],
                ['like', self::tableName() . '.event_location', $searchString],
                ['like', self::tableName() . '.event_address', $searchString],
                ['like', self::tableName() . '.event_address_house_number', $searchString],
                ['like', self::tableName() . '.event_address_cap', $searchString],
            ];

            if ($enableTagSearch) {
                if ($tagTranslationSearch) {
                    $orQueries[] = ['like', 'tt.nome', $searchString];
                }
                $orQueries[] = ['like', 't.nome', $searchString];
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
        $searchResult->box_type = "image";
        $searchResult->id = $model->id;
        $searchResult->titolo = $model->title;
        $searchResult->data_pubblicazione = $model->publication_date_begin;
        $searchResult->immagine = $model->getEventLogo();
        $searchResult->abstract = $model->summary;

        return $searchResult;
    }
    /**
     * CmsModelInterface
     * */

    /**
     * get the query used by the related searchHomepageNews method
     * return just the query in case data provider/query itself needs editing
     *
     * @param array $params
     * @return ActiveQuery
     */
    public function homepageEventQuery($params)
    {
        $now = date('Y-m-d');
        $tableName = $this->tableName();
        $query = $this->baseSearch($params)
            ->andWhere([
                $tableName . '.status' => Event::EVENTS_WORKFLOW_STATUS_PUBLISHED,
                $tableName . '.primo_piano' => 1
            ])
            ->andWhere(['<=', 'publication_date_begin', $now])
            ->andWhere(['or',
                    ['>=', 'publication_date_end', $now],
                    ['publication_date_end' => null]]
            );

        return $query;
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
        $params = array_merge($params, Yii::$app->request->get());
        $this->load($params);
        $query = $this->homepageEventQuery($params);
        $this->applySearchFilters($query);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'publication_date_begin' => SORT_DESC,
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
        array_push($viewFields,
            new CmsField("title", "TEXT", 'amosevents',
                $this->attributeLabels()["title"]));
        array_push($viewFields,
            new CmsField("summary", "TEXT", 'amosevents',
                $this->attributeLabels()['summary']));
        array_push(
            $viewFields,
            new CmsField("eventLogo", "IMAGE", 'amosevents',
                $this->attributeLabels()['eventLogo'])
        );
        array_push(
            $viewFields,
            new CmsField("begin_date_hour", "DATE", 'amosevents',
                $this->attributeLabels()['begin_date_hour'])
        );
        array_push(
            $viewFields,
            new CmsField("end_date_hour", "DATE", 'amosevents',
                $this->attributeLabels()['end_date_hour'])
        );

        return $viewFields;
    }

    /**
     *
     * @return array
     */
    public function cmsSearchFields()
    {
        $searchFields = [];

        array_push($searchFields, new CmsField("title", "TEXT"));
        array_push($searchFields, new CmsField("summary", "TEXT"));
        array_push($searchFields, new CmsField("begin_date_hour", "DATE"));

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
        if (isset($id)) {
            $md = $this->findOne($id);
            if (!is_null($md)) {
                $retValue = $md->visible_in_the_calendar;
            }
        }

        return $retValue;
    }

    /**
     * @param array $params
     * @param int $limit
     * @return ActiveQuery
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    public function ultimeEventsQuery($params, $limit)
    {
        $query = $this->buildQuery($params, 'own-interest', false);
        $this->notificationOff($query);
        $query
            ->joinWith('eventType')
            ->andFilterWhere(
                [
                    'id' => $this->id,
                    'event_type_id' => $this->event_type_id,
                    'created_at' => $this->created_at,
                    'updated_at' => $this->updated_at,
                    'deleted_at' => $this->deleted_at,
                    'created_by' => $this->created_by,
                    'updated_by' => $this->updated_by,
                    'deleted_by' => $this->deleted_by,
                ]
            )
            ->andFilterWhere(['like', self::tableName() . '.title', $this->title])
            ->andFilterWhere(['like', self::tableName() . '.description', $this->description])
            ->andFilterWhere(['>=', self::tableName() . '.begin_date_hour', $this->begin_date_hour])
            ->andFilterWhere(['<=', self::tableName() . '.end_date_hour', $this->end_date_hour]);
        return $query;
    }

    /**
     * Method that search the latest research events validated, typically limit is $ 3.
     *
     * @param array $params
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function ultimeEvents($params, $limit)
    {
        $query = $this->ultimeEventsQuery($params, $limit);
        $provider = new ActiveDataProvider(
            [
                'query' => $query,
                'sort' => [
                    'defaultOrder' => [
                        'begin_date_hour' => SORT_DESC,
                    ]
                ],
            ]
        );
        return $provider;
    }

    /**
     *
     * @param array $params
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchNews($params, $limit = null)
    {
        $queryParams = [];
        $dataProvider = null;
        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                $queryParams = ArrayHelper::merge($queryParams,
                    eval("return " . $command . ";"));
            }
        }
        $model = $this->findOne(['id' => $queryParams['event_id']]);
        if (!is_null($model)) {
            unset($queryParams["event_id"]);
            EventsUtility::setScope($model->community_id);
            $modelSearch = new NewsSearch();
            $dataProvider = $modelSearch->searchOwnNews([]);
            $query = $dataProvider->query;
            if (count($queryParams) > 0) {
                foreach ($queryParams as $key => $param) {
                    $query->andWhere([$key => $param]);
                }
            }
        }

        return $dataProvider;
    }

    /**
     *
     * @param array $params
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchProtagonists($params, $limit = null)
    {
        $queryParams = [];
        $dataProvider = null;
        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                $queryParams = ArrayHelper::merge($queryParams,
                    eval("return " . $command . ";"));
            }
        }
        $model = $this->findOne(['id' => $queryParams['event_id']]);
        if (!is_null($model)) {
            unset($queryParams["event_id"]);
            $protagonists = new EventLandingProtagonistSearch();
            $dataProvider = $protagonists->search($params);
            $query = $dataProvider->query;
            $query->andWhere(['event_landing_id' => $model->eventLanding->id]);
            if (count($queryParams) > 0) {
                foreach ($queryParams as $key => $param) {
                    $query->andWhere([$key => $param]);
                }
            }
        }
        return $dataProvider;
    }

    /**
     *
     * @param array $params
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchGallery($params, $limit = null)
    {
        $queryParams = [];
        $dataProviderSliderElemImage = null;
        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                $queryParams = ArrayHelper::merge($queryParams,
                    eval("return " . $command . ";"));
            }
        }
        $model = $this->findOne(['id' => $queryParams['event_id']]);
        if (!is_null($model)) {
            unset($queryParams["event_id"]);
            EventsUtility::setScope($model->community_id);
            $slider = $model->eventLanding->imageSlider;
            if (!is_null($slider)) {
                $dataProviderSliderElemImage = new ActiveDataProvider(['query' => $slider->getSliderElems()->orderBy('order ASC')]);
            }
        }

        return $dataProviderSliderElemImage;
    }

    /**
     *
     * @param array $params
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function cmsSearchVideo($params, $limit = null)
    {
        $queryParams = [];
        $dataProviderSliderElemVideo = null;
        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                $queryParams = ArrayHelper::merge($queryParams,
                    eval("return " . $command . ";"));
            }
        }
        $model = $this->findOne(['id' => $queryParams['event_id']]);
        if (!is_null($model)) {
            unset($queryParams["event_id"]);
            EventsUtility::setScope($model->community_id);
            $sliderVideo = $model->eventLanding->videoSlider;
            if (!is_null($sliderVideo)) {
                $dataProviderSliderElemVideo = new ActiveDataProvider(['query' => $sliderVideo->getSliderElems()->orderBy('order ASC')]);
            }
        }

        return $dataProviderSliderElemVideo;
    }

    /**
     * @param $params
     * @return ActiveQuery
     */
    public function searchMyRegistrationQuery($params)
    {
        return $query = $this->buildQuery($params, 'my-registrations');

    }

    /**
     * @return null|ActiveDataProvider
     * @throws InvalidConfigException
     */
    public function cmsChildrenSearch($params, $limit = null)
    {
        $queryParams = [];
        $dataProviderEvents = null;
        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                $queryParams = ArrayHelper::merge($queryParams,
                    eval("return " . $command . ";"));
            }
        }
        $model = $this->findOne(['id' => $queryParams['event_id']]);
        if (!is_null($model)) {
            unset($queryParams["event_id"]);
            $dataProviderEvents = new ActiveDataProvider(['query' => Event::find()->andWhere(['event_id' => $model->id])
                ->andWhere(['status' => Event::EVENTS_WORKFLOW_STATUS_PUBLISHED])
                ->orderBy('begin_date_hour ASC')]);
        }
        return $dataProviderEvents;
    }

    /**
     * @param $params
     * @param null $limit
     * @return null|ActiveDataProvider
     * @throws InvalidConfigException
     */
    public function cmsStreamingSearch($params, $limit = null)
    {
        $queryParams = [];
        $dataProviderEvents = null;
        if (!empty($params["conditionSearch"])) {
            $commands = explode(";", $params["conditionSearch"]);
            foreach ($commands as $command) {
                $queryParams = ArrayHelper::merge($queryParams,
                    eval("return " . $command . ";"));
            }
        }
        $model = $this->findOne(['id' => $queryParams['event_id']]);
        if (!is_null($model)) {
            unset($queryParams["event_id"]);
            $dataProviderEvents = new ActiveDataProvider(['query' => EventLanding::find()->andWhere(['event_id' => $model->id])]);
        }
        return $dataProviderEvents;

    }


    /**
     * Search method useful to retrieve news to show in frontend (with cms)
     *
     * @param $params
     * @param int|null $limit
     * @return ActiveDataProvider
     */
    public function cmsPublishedSearch($params, $limit = null)
    {
        date_default_timezone_set('Europe/Rome');

        $params = array_merge($params, Yii::$app->request->get());
        $this->load($params);
        $now = date('Y-m-d H:i:s');
        $tableName = $this->tableName();
        $query = $this->baseSearch($params)
            ->innerJoinWith('eventType')
            ->andWhere(['!= ', 'event_type.event_type', EventType::TYPE_UPON_INVITATION])
            ->andWhere([
                $tableName . '.status' => Event::EVENTS_WORKFLOW_STATUS_PUBLISHED,
            ])
            ->andWhere(['<=', 'publication_date_begin', $now])
            ->andWhere(['or',
                    ['>=', 'publication_date_end', $now],
                    ['publication_date_end' => null]]
            )
            ->andWhere(['or',
                    ['>=', 'end_date_hour', $now],
                    ['end_date_hour' => null]]
            )->andWhere(['event.event_id' => null]);

        $this->applySearchFilters($query);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'begin_date_hour' => SORT_ASC,
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
     * @param $params
     */
    public function getRssFeedQuery($params)
    {
        date_default_timezone_set('Europe/Rome');

        $params = array_merge($params, Yii::$app->request->get());
        $this->load($params);
        $now = date('Y-m-d H:i:s');

//        $publicationDateTime =  new \DateTime();
//        if (!empty($params['publication_date'])) {
//            $publicationDateTime = new \DateTime($params['publication_date']);
//        }
//        $dateInterval = new \DateInterval('PT30D');
//        if (!empty($params['interval'])) {
//            $dateInterval = new \DateInterval('PT' . $params['interval'] . 'D');
//        }
//        $publicationDateTime = $publicationDateTime->sub($dateInterval);

        $tableName = $this->tableName();

        $query = $this->baseSearch($params)
            ->innerJoinWith('eventType')
            ->andWhere(['!= ', 'event_type.event_type', EventType::TYPE_UPON_INVITATION])
            ->andWhere([
                $tableName . '.status' => Event::EVENTS_WORKFLOW_STATUS_PUBLISHED,
            ])
            ->andWhere(['<=', 'publication_date_begin', $now])
            ->andWhere(['or',
                    ['>=', 'publication_date_end', $now],
                    ['publication_date_end' => null]]
            )
            ->andWhere(['or',
                    ['>=', 'end_date_hour', $now],
                    ['end_date_hour' => null]]
            )->andWhere(['event.event_id' => null]);

        if (!empty($params['code'])) {
            $query
                ->leftJoin('entitys_tags_mm', 'event.id = entitys_tags_mm.record_id')
                ->leftJoin('tag', 'tag.id = entitys_tags_mm.tag_id')
                ->andWhere(['entitys_tags_mm.classname' => Event::className()])
                ->andWhere(['entitys_tags_mm.deleted_at' => null])
                ->andWhere(['tag.codice' => $params['code']]);
        }

        if(!empty($params['limit'])){
            $query->limit($params['limit']);
        }
        return $query;

//        $this->applySearchFilters($query);
    }

}