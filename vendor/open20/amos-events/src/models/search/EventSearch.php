<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events
 * @category   CategoryName
 */

namespace open20\amos\events\models\search;

use open20\amos\community\AmosCommunity;
use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\interfaces\CmsModelInterface;
use open20\amos\core\interfaces\SearchModelInterface;
use open20\amos\core\record\CmsField;
use open20\amos\core\record\SearchResult;
use open20\amos\cwh\AmosCwh;
use open20\amos\cwh\query\CwhActiveQuery;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventCalendarsSlots;
use open20\amos\events\models\EventMembershipType;
use open20\amos\notificationmanager\AmosNotify;
use open20\amos\notificationmanager\base\NotifyWidget;
use open20\amos\notificationmanager\base\NotifyWidgetDoNothing;
use open20\amos\notificationmanager\models\NotificationChannels;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\db\ExpressionInterface;
use yii\di\Container;
use yii\di\NotInstantiableException;
use yii\helpers\ArrayHelper;
use open20\amos\tag\models\EntitysTagsMm;

/**
 * Class EventSearch
 * EventSearch represents the model behind the search form about `open20\amos\events\models\Event`.
 * @package open20\amos\events\models\search
 */
class EventSearch extends Event implements SearchModelInterface, CmsModelInterface {

    /**
     * @var Container $container
     */
    private $container;
    public $eventsModule;

    /**
     * @inheritdoc
     */
    public function __construct(array $config = []) {
        $this->container = new Container();
        $this->container->set('notify', new NotifyWidgetDoNothing());

        $this->eventsModule = \Yii::$app->getModule(AmosEvents::getModuleName());

        parent::__construct($config);
        /** @var Event $eventModel */
        $eventModel = AmosEvents::instance()->createModel('Event');
        $this->modelClassName = $eventModel::className();
        $this->modelFormName = $eventModel->formName();
    }

    /**
     * @return object
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    public function getNotifier() {
        return $this->container->get('notify');
    }

    /**
     * @param $notifier
     */
    public function setNotifier(NotifyWidget $notifier) {
        $this->container->set('notify', $notifier);
    }

    /**
     * @param ActiveQuery $query
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    private function notificationOff($query) {
        $notify = $this->getNotifier();

        if ($notify) {
            /** @var AmosNotify $notify */
            $notify->notificationOff(
                    \Yii::$app->getUser()->id,
                    $this->eventsModule->model('Event'),
                    $query,
                    NotificationChannels::CHANNEL_READ
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function rules() {
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
                    'deleted_at'
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
    public function scenarios() {
        return Model::scenarios();
    }

    /**
     */
    public function behaviors() {
        $parentBehaviors = parent::behaviors();

        $behaviors = [];
        //if the parent model News is a model enabled for tags, NewsSearch will have TaggableBehavior too
        $moduleTag = \Yii::$app->getModule('tag');
        if (isset($moduleTag) && in_array(
                        $this->eventsModule->model('Event'),
                        $moduleTag->modelsEnabled
                ) && $moduleTag->behaviors) {
            $behaviors = ArrayHelper::merge($moduleTag->behaviors, $behaviors);
        }

        return ArrayHelper::merge($parentBehaviors, $behaviors);
    }

    /**
     * @param $params
     * @return ActiveQuery $query
     */
    public function baseSearch($params) {
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
     * 
     * @return array
     */
    public function createOrderClause() {

        return [
            'attributes' => [
                $this->orderAttribute,
            ],
            'defaultOrder' => [
                $this->orderAttribute => (int) $this->orderType,
            ]
        ];
    }

    /**
     * @param array $params
     * @param string $queryType
     * @param int|null|ExpressionInterface $limit
     * @param bool $onlyDrafts
     * @return ActiveDataProvider|\yii\data\BaseDataProvider
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    public function search($params, $queryType = null, $limit = null, $onlyDrafts = false, $pageSize = null) {
        $query = $this->buildQuery($params, $queryType, $onlyDrafts);

        $dataProvider = new ActiveDataProvider(
                [
            'query' => $query,
                ]
        );

        $dataProvider = $this->searchDefaultOrder($dataProvider);

        if (!empty($params["withPagination"])) {
            $dataProvider->setPagination(['pageSize' => $limit]);
            $query->limit(null);
        } else {
            $query->limit($limit);
        }
        $this->notificationOff($query);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // Verifico se il modulo supporta i TAG e, in caso, ricerco anche fra quelli
        if (isset($params[$this->formName()]['tagValues'])) {

            $tagValues = $params[$this->formName()]['tagValues'];

            $this->setTagValues($tagValues);
            if (is_array($tagValues) && !empty($tagValues)) {
                $orQueries = null;
                $i = 0;
                foreach ($tagValues as $rootId => $tagId) {
                    if (!empty($tagId)) {
                        if ($i == 0) {
                            $query->innerJoin('entitys_tags_mm entities_tag',
                                    "entities_tag.classname = '" . addslashes($this->eventsModule->model('Event')) . "' AND entities_tag.record_id=" . static::tableName() . ".id");
                            $orQueries[] = 'or';
                        }
                        $tags = explode(',', $tagId);
                        $tags = array_unique($tags);
                        $orQueries[] = ['and', ["entities_tag.tag_id" => $tags], ['entities_tag.root_id' => $rootId], ['entities_tag.deleted_at' => null]];
                        $i++;
                    }
                }
                if (!empty($orQueries)) {
                    $query->andWhere($orQueries);
                }
            }
        }
        $query
                ->joinWith('eventType')
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
                ->andFilterWhere(['<=', new Expression('DATE(' . self::tableName() . '.end_date_hour)'), $this->end_date_hour]);

        // AGID 
        if ($this->eventsModule->enableAgid) {
            if (!empty($params['EventSearch']['status'])) {
                $query = $query->andWhere(['status' => $params['EventSearch']['status']]);
            }
        }



        return $dataProvider;
    }

    /**
     * Search for events own interest if logged. all event if not
     *
     * @param array $params $_GET search parametrs
     * @param int|null $limit Query limit
     * @return ActiveDataProvider
     */
    public function cmsSearchOwnInterest($params, $limit = null) {
        if (\Yii::$app->user->isGuest) {
            $dataProvider = $this->cmsSearch($params, $limit);
        } else {
            $dataProvider = $this->searchOwnInterest($params, $limit);
        }

        $newDate = date('Y-m-d');
        $dataProvider->query->andWhere(
                new \yii\db\Expression("DATE(event.begin_date_hour) >= '$newDate' OR (DATE(event.begin_date_hour) < '$newDate' AND DATE(event.end_date_hour) >= '$newDate')")
        );
        if (empty(\Yii::$app->request->get('sort'))) {
            $dataProvider->query->orderBy('begin_date_hour asc, end_date_hour asc');
        }

        if (!empty($params["withPagination"])) {
            $dataProvider->setPagination(['pageSize' => $limit]);
            $dataProvider->query->limit(null);
        } else {
            $dataProvider->query->limit($limit);
        }

        return $dataProvider;
    }

    /**
     * Search for events created by the logged user
     *
     * @param array $params $_GET search parametrs
     * @param int|null $limit Query limit
     * @return ActiveDataProvider
     */
    public function searchCreatedBy($params, $limit = null) {
        return $this->search($params, 'created-by', $limit);
    }

    /**
     * @param ActiveQuery $query
     */
    private function filterByMembershipType($query) {
        $loggedUserId = Yii::$app->getUser()->id;
        $query
                ->leftJoin(
                        'community_user_mm',
                        'community_user_mm.community_id = event.community_id AND community_user_mm.user_id=' . $loggedUserId
                )
                ->andWhere(
                        'event.event_membership_type_id !=' . EventMembershipType::TYPE_ON_INVITATION . ' OR 
        ( event.event_membership_type_id = ' . EventMembershipType::TYPE_ON_INVITATION . ' AND community_user_mm.user_id = ' . $loggedUserId . ' AND community_user_mm.deleted_at is null )
         OR  ( event.event_management = 0 AND event.event_membership_type_id is null )'
        );
    }

    /**
     * Search for all events
     *
     * @param array $params $_GET search parametrs
     * @param int|null $limit Query limit
     * @return ActiveDataProvider
     */
    public function searchAllEvents($params, $limit = null) {
        return $this->search($params, 'all', $limit);
    }

    /**
     * Search the events the logged user is subscribed to.
     *
     * @param array $params $_GET search parametrs
     * @param int|null $limit Query limit
     * @return ActiveDataProvider
     */
    public function searchSubscribedEvents($params, $limit = null) {
        /** @var AmosCommunity $communityModule */
        $communityModule = AmosCommunity::instance();

        /** @var Community $communityModel */
        $communityModel = $communityModule->createModel('Community');
        $communityTable = $communityModel::tableName();
        $communityUserMmTable = CommunityUserMm::tableName();

        $dataProvider = $this->search($params, 'all', $limit);
        $dataProvider->query->innerJoin($communityTable, $communityTable . '.id = ' . self::tableName() . '.community_id');
        $dataProvider->query->innerJoin($communityUserMmTable, $communityUserMmTable . '.community_id = ' . self::tableName() . '.community_id');
        $dataProvider->query->andWhere([$communityTable . '.deleted_at' => null]);
        $dataProvider->query->andWhere([$communityUserMmTable . '.deleted_at' => null]);
        $dataProvider->query->andWhere([$communityUserMmTable . '.user_id' => Yii::$app->user->id]);
        $dataProvider->query->andWhere([$communityUserMmTable . '.status' => CommunityUserMm::STATUS_ACTIVE]);
        $dataProvider->query->andWhere([$communityUserMmTable . '.role' => $this->getBaseRole()]);

        return $dataProvider;
    }

    /**
     * Search for events visible by the logged user and published on the calendar
     *
     * @param array $params $_GET search parametrs
     * @param int|null $limit Query limit
     * @return ActiveDataProvider
     */
    public function searchCalendarView($params, $limit = null) {
        $dataProvider = $this->search($params, 'own-interest', $limit);
        $newDate = date('Y-m-d');
        $dataProvider->query->andWhere(
                new \yii\db\Expression("DATE(event.begin_date_hour) >= '$newDate' OR (DATE(event.begin_date_hour) < '$newDate' AND DATE(event.end_date_hour) >= '$newDate')")
        );
        if (empty(\Yii::$app->request->get('sort'))) {
            $dataProvider->query->orderBy('begin_date_hour asc, end_date_hour asc');
        }
        return $dataProvider;
    }

    public function searchCalendarViewAll($params, $limit = null) {
        if (\Yii::$app->user->isGuest) {
            $dataProvider = $this->cmsSearch($params, $limit);
        } else {
            $dataProvider = $this->search($params, 'all', $limit);
        }

        $newDate = date('Y-m-d');
        $dataProvider->query->andWhere(
                new \yii\db\Expression("DATE(event.begin_date_hour) >= '$newDate' OR (DATE(event.begin_date_hour) < '$newDate' AND DATE(event.end_date_hour) >= '$newDate')")
        );
        if (empty(\Yii::$app->request->get('sort'))) {
            $dataProvider->query->orderBy('begin_date_hour asc, end_date_hour asc');
        }

        return $dataProvider;
    }

    /**
     * Search for events that by the logged user has permission to validate
     *
     * @param array $params $_GET search parametrs
     * @param int|null $limit Query limit
     * @return ActiveDataProvider
     */
    public function searchToPublish($params, $limit = null) {
        return $this->search($params, 'to-validate', $limit);
    }

    /**
     * Search for events where the the logged user is part of the staff
     *
     * @param array $params $_GET search parametrs
     * @param int|null $limit Query limit
     * @return ActiveDataProvider
     */
    public function searchManagement($params, $limit = null) {
        if (\Yii::$app->user->isGuest) {
            return $this->cmsSearch($params, $limit);
        } else {
            return $this->search($params, 'all', $limit);
        }
    }

    /**
     * Search for events where the the logged user is part of the staff
     *
     * @param array $params $_GET search parametrs
     * @param int|null $limit Query limit
     * @return ActiveDataProvider
     */
    public function searchAdmin($params, $limit = null) {
        return $this->search($params, 'admin', $limit);
    }

    /**
     * @param string $queryType
     * @param array $params
     * @return ActiveQuery $query
     */
    public function buildQuery($params, $queryType, $onlyDrafts = false) {
        $query = $this->baseSearch($params);
        $classname = $this->eventsModule->model('Event');

        /** @var  AmosCwh $moduleCwh */
        $moduleCwh = \Yii::$app->getModule('cwh');
        $cwhActiveQuery = null;

        $isSetCwh = isset($moduleCwh) && in_array($classname, $moduleCwh->modelsEnabled);
        if ($isSetCwh) {
            $moduleCwh->setCwhScopeFromSession();
            $cwhActiveQuery = new CwhActiveQuery(
                    $classname, [
                'queryBase' => $query
                    ]
            );
        }

        switch ($queryType) {
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
                if ($isSetCwh) {
                    $query = $cwhActiveQuery->getQueryCwhAll(null, null, true);
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
            case 'admin':
                // show all events in all statuses
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

        return $query;
    }

    /**
     * Search method useful to retrieve events in validated status with both flags
     * publish_in_the_calendar and visible_in_the_calendar true
     *
     * @param array $params Array di parametri
     * @return ActiveDataProvider
     */
    public function searchHighlightedAndHomepageEvents($params) {
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
    public function highlightedAndHomepageEventsQuery($params) {
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
    public function globalSearch($searchParamsArray, $pageSize = 5) {
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
                        $this->eventsModule->model('Event'),
                        $moduleTag->modelsEnabled
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
                $dataProvider->query->leftJoin('tag__translation tt', "e_tag.tag_id=tt.tag_id");
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
    public function convertToSearchResult($model) {
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
     * @return \yii\db\ActiveQuery
     */
    public function homepageEventQuery($params) {
        $now = date('Y-m-d H:i:s');
        $tableName = $this->tableName();
        $newDate = date('Y-m-d');
        $query = $this->baseSearch($params)
                ->leftJoin(EntitysTagsMm::tableName(), EntitysTagsMm::tableName() . ".classname = '" . addslashes(Event::className()) . "' and " . EntitysTagsMm::tableName() . ".record_id = " . Event::tableName() . ".id")
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
    public function cmsSearch($params, $limit = null) {
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

        // AGID 
        if ($this->eventsModule->enableAgid) {
            $query->orderBy(['begin_date_hour' => SORT_DESC]);
        }

        return $dataProvider;
    }

    /**
     *
     * @return array
     */
    public function cmsViewFields() {
        $viewFields = [];
        array_push($viewFields, new CmsField("title", "TEXT", 'amosevents', $this->attributeLabels()["title"]));
        array_push($viewFields, new CmsField("summary", "TEXT", 'amosevents', $this->attributeLabels()['summary']));
        array_push(
                $viewFields,
                new CmsField("eventLogo", "IMAGE", 'amosevents', $this->attributeLabels()['eventLogo'])
        );
        array_push(
                $viewFields,
                new CmsField("begin_date_hour", "DATE", 'amosevents', $this->attributeLabels()['begin_date_hour'])
        );
        array_push(
                $viewFields,
                new CmsField("end_date_hour", "DATE", 'amosevents', $this->attributeLabels()['end_date_hour'])
        );

        return $viewFields;
    }

    /**
     *
     * @return array
     */
    public function cmsSearchFields() {
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
    public function cmsIsVisible($id) {
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
    public function ultimeEventsQuery($params, $limit) {
        $query = $this->buildQuery($params, 'all', false);
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
    public function ultimeEvents($params, $limit) {
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
     * @param $params
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function mySlotsAllCmsSearch($params, $limit = null) {

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
        $query = EventCalendarsSlots::find()
                ->innerJoinWith('eventCalendars')
                ->innerJoin('event_calendars_slots_booked', 'event_calendars_slots.id = event_calendars_slots_booked.event_calendars_slots_id')
                ->andWhere(['event_calendars_slots_booked.deleted_at' => null])
                ->andWhere(['event_calendars_slots_booked.user_id' => \Yii::$app->user->id])
                ->andFilterWhere(['event_id' => $model->id]);

        $this->load($params);
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        return $dataProvider;
    }
}
