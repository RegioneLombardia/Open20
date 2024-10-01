<?php

namespace open20\amos\mobile\bridge\modules\v1\controllers;

use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileMailUtility;
use open20\amos\core\record\CachedActiveQuery;
use open20\amos\core\response\Response;
use open20\amos\discussioni\models\DiscussioniTopic;
use open20\amos\documenti\models\Documenti;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventInvitation;
use open20\amos\events\models\EventParticipantCompanion;
use open20\amos\events\models\EventPushNotification;
use open20\amos\events\models\search\EventSearch;
use open20\amos\events\utility\EventsUtility;
use open20\amos\mobile\bridge\modules\v1\actions\entitydata\parsers\DiscussioniParser;
use open20\amos\mobile\bridge\modules\v1\actions\entitydata\parsers\DocumentiParser;
use open20\amos\mobile\bridge\modules\v1\actions\entitydata\parsers\EventPlatformParser;
use open20\amos\mobile\bridge\modules\v1\actions\entitydata\parsers\NewsParser;
use open20\amos\mobile\bridge\modules\v1\actions\entitydata\parsers\PartecipantsParser;
use open20\amos\mobile\bridge\modules\v1\actions\entitydata\parsers\SondaggiParser;
use open20\amos\mobile\bridge\modules\v1\utility\EventUtility;
use open20\amos\news\models\News;
use open20\amos\sondaggi\models\search\SondaggiSearch;
use open20\amos\tag\models\Tag;
use Exception;
use kartik\mpdf\Pdf;
use luya\web\filters\ResponseCache;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\log\Logger;
use yii\rest\Controller;
use common\models\AppVersion;

class EventPlatformController extends DefaultController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviours = parent::behaviors();

        $behaviours =  ArrayHelper::merge($behaviours,
            [
                'verbFilter' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'events-list' => ['get'],
                        'general-elastic-search' => ['get'],
                        'events-list-home' => ['get'],
                        'my-events' => ['get'],
                        'event-detail' => ['get'],
                        'event-news-list' => ['get'],
                        'event-discussions-list' => ['get'],
                        'event-documenti-list' => ['get'],
                        'event-sondaggi-list' => ['get'],
                        'event-qr-code' => ['get'],
                        'event-user-role' => ['get'],
                        'event-ticket' => ['get'],
                        'event-has-ticket' => ['get'],
                        'event-changes' => ['get'],
                        'delete-account' => ['get'],
                        'history-push-notification' => ['get'],
                        'get-last-app-version' => ['get'],
                    ],
                ],
            ]);


        $behaviours['pageCache'] = EventUtility::mobileCacheConfigs();

        return $behaviours;
    }

    public function beforeAction($action)
    {
        if (\Yii::$app->request->get('language')) {
            \Yii::$app->language = \Yii::$app->request->get('language');
        } else {
            \Yii::$app->language = 'it-IT';
        }

        return parent::beforeAction($action);

    }

    public function actionGeneralElasticSearch($searchtext, $pageSize = null, $pageNumber = 0)
    {
        $modelSearch = new \common\modules\elasticsearch\ElasticModelSearch();
        $dataProvider = $modelSearch->cmsSearch(
            [
                'searchtext' => $searchtext,
                "withPagination" => 1
            ], intval($pageSize));

        // Add pagination to dataProvider
        if (is_null($pageSize)) {
            $dataProvider->pagination = false;
        } else {
            $dataProvider->pagination->pageSize = $pageSize;
            $dataProvider->pagination->page = $pageNumber;
        }

        $listModel = $dataProvider->getModels();
        foreach ($listModel as $model) {
            $event = Event::findOne($model['id']);
            if($event) {
                $list[] = EventPlatformParser::parseItem($event);
            }
        }



        // Api Pagination
        $totalRowCount = $dataProvider->totalCount;
        return [
            'totalRowCount' => intval($totalRowCount),
            'pageSize' => intval($pageSize),
            'pageNumber' => intval($pageNumber),
            'list' => $list
        ];
    }

    /**
     * @param $pageSize
     * @param $pageNumber
     * @return array
     */
    public function actionMyEvents($pageSize = null, $pageNumber = 0, $user_id = null){

        $search = new EventSearch();

        /** @var  $dataProvider ActiveDataProvider */
        $dataProvider = $search->searchMyRegistrations(['user_id' => $user_id], $pageSize);
        return $this->paginationSearch($dataProvider, $pageSize, $pageNumber);
    }



    /**
     * @param $pageSize
     * @param $pageNumber
     * @param $filterDate
     * @param $filterTag
     * @return array
     */
    public function actionEventsListHome($pageSize = null, $pageNumber = 0, $filterDate = null, $filterTag = null)
    {
        $params = [];
        $search = new EventSearch();
        $params['day'] = $filterDate;
        $params['tag_id'] = $filterTag;

        /** @var  $dataProvider ActiveDataProvider */
        $dataProvider = $search->cmsPublishedSearch($params, $pageSize);

        return $this->paginationSearch($dataProvider, $pageSize, $pageNumber);
    }

    /**
     * @param $dataProvider
     * @param $pageSize
     * @param $pageNumber
     * @return array
     */
    public function paginationSearch($dataProvider, $pageSize,$pageNumber){
        $list = [];
        // Add pagination to dataProvider
        if (is_null($pageSize)) {
            $dataProvider->pagination = false;
        } else {
            $dataProvider->pagination->pageSize = $pageSize;
            $dataProvider->pagination->page = $pageNumber;
        }

        // Api Pagination
        $totalRowCount = $dataProvider->totalCount;
        $listModel = $dataProvider->getModels();

        foreach ($listModel as $model) {
            $list[] = EventPlatformParser::parseItem($model);
        }
        return [
            'totalRowCount' => intval($totalRowCount),
            'pageSize' => intval($pageSize),
            'pageNumber' => intval($pageNumber),
            'list' => $list
        ];
    }

    /**
     *
     * @return type
     */
    public function actionEventsList($offset = null, $limit = null, $from_date = null, $to_date = null)
    {
        $list = [];
        try {
            $eventsModule = AmosEvents::instance();
            $eventTypeModel = $eventsModule->model('EventType');
            $params = [];
            $search = new EventSearch();
            if (!is_null($offset)) {
                $params['offest'] = $offset;
            }
            if (!is_null($limit)) {
                $params['limit'] = $limit;
            }
            if (!is_null($from_date)) {
                $search->begin_date_hour;
            }
            //$cwh          = $this->loadCwh();
            //$cwh->resetCwhScopeInSession();
            $dataProvider = $search->searchMyInvitations($params);
            $subquery = $dataProvider->query;
            $subquery->select(Event::tableName() . '.id');
            $subquery->andWhere(['>=', 'end_date_hour', new Expression('NOW()')]);

            $dataProvider2 = $search->searchMyRegistrations($params);
            $subquery2 = $dataProvider2->query;
            $subquery2->select(Event::tableName() . '.id');
            $subquery2->andWhere(['>=', 'end_date_hour', new Expression('NOW()')]);
            $now = date('Y-m-d H:i:s');
            $query = Event::find();
            $query->innerJoinWith('eventType')
                ->andWhere(['OR',
                    ['!= ', 'event_type.event_type', $eventTypeModel::TYPE_UPON_INVITATION],
                    ['AND',
                        ['=', 'event_type.event_type', $eventTypeModel::TYPE_UPON_INVITATION],
                        ['in', Event::tableName() . '.id', $subquery],
                    ],
                    ['AND',
                        ['=', 'event_type.event_type', $eventTypeModel::TYPE_UPON_INVITATION],
                        ['in', Event::tableName() . '.id', $subquery2],
                    ],
                ])
                ->andWhere([Event::tableName() . '.status' => Event::EVENTS_WORKFLOW_STATUS_PUBLISHED,])
                ->andWhere(['<=', 'publication_date_begin', $now])
                ->andWhere(['or',
                        ['>=', 'publication_date_end', $now],
                        ['publication_date_end' => null]]
                )
                ->andWhere(['or',
                        ['>=', 'end_date_hour', $now],
                        ['end_date_hour' => null]]
                )->andWhere(['event.event_id' => null]);


//            $query->innerJoin($eventTypeModel::tableName(), $eventTypeModel::tableName() .'.id = '. Event::tableName() . '.event_type_id');
//            $query->andWhere(
//                ['or',
//                    [
//                        'and',
//                        [$eventTypeModel::tableName() .'.event_type' => $eventTypeModel::TYPE_OPEN],
//                        [$eventTypeModel::tableName() .'.limited_seats' => 0]
//                    ],
//                    [ $eventTypeModel::tableName() .'.patronage' => 1],
//                    ['or',
//                       [$eventTypeModel::tableName() .'.event_type' => $eventTypeModel::TYPE_INFORMATIVE],
//                       ['or',
//                           ['in', Event::tableName() .'.id', $subquery ], ['in', Event::tableName() .'.id', $subquery2 ]
//                       ]
//                    ]
//                ]);
//
//            $query->andWhere(['>=', 'end_date_hour', new Expression('NOW()')]);
//            $query->addOrderBy(['begin_date_hour' => SORT_ASC]);

            $query->limit($limit);
            $dataProvider->query = $query;
            $listModel = $dataProvider->getModels();
            foreach ($listModel as $model) {
                $list[] = EventPlatformParser::parseItem($model);
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return $list;
    }

    /**
     *
     * @param type $event_id
     * @return type
     */
    public function actionEventDetail($event_id)
    {
        $ditail = [];

        try {
            $ditail = EventPlatformParser::parseItem(Event::findOne(['id' => $event_id]), true);
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return $ditail;
    }

    /**
     *
     * @param type $event_id
     * @param type $offset
     * @param type $limit
     * @return array
     */
    public function actionEventNewsList($event_id, $offset = null, $limit = null)
    {
        $list = [];
        try {
            $event = Event::findOne(['id' => $event_id]);
            if (!is_null($event)) {
                $cwh = $this->loadCwh();
                $old_scope = $cwh->getCwhScope();
                $cwh->setCwhScopeInSession([
                    'community' => $event->community_id
                ]);
                $namespace = News::className();
                $bodyParams = [
                    'namespace' => $namespace,
                    'offset' => $offset,
                    'limit' => $limit
                ];
                $list = NewsParser::getItems($namespace, $bodyParams);
                $cwh->setCwhScopeInSession($old_scope);
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return $list;
    }

    /**
     *
     * @param type $event_id
     * @param type $offset
     * @param type $limit
     * @return type
     */
    public function actionEventDiscussionsList($event_id, $offset = null,
                                               $limit = null)
    {
        $list = [];
        try {
            $event = Event::findOne(['id' => $event_id]);
            if (!is_null($event)) {
                $cwh = $this->loadCwh();
                $old_scope = $cwh->getCwhScope();
                $cwh->setCwhScopeInSession([
                    'community' => $event->community_id
                ]);
                $namespace = DiscussioniTopic::className();
                $bodyParams = [
                    'namespace' => $namespace,
                    'offset' => $offset,
                    'limit' => $limit
                ];
                $list = DiscussioniParser::getItems($namespace,
                    $bodyParams);
                $cwh->setCwhScopeInSession($old_scope);
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return $list;
    }


    /**
     *
     * @param type $event_id
     * @param type $offset
     * @param type $limit
     * @return type
     */
    public function actionEventDocumentiList($event_id, $offset = null,
                                             $limit = null)
    {
        $list = [];
        try {
            $event = Event::findOne(['id' => $event_id]);
            if (!is_null($event)) {
                $cwh = $this->loadCwh();
                $old_scope = $cwh->getCwhScope();
                $cwh->setCwhScopeInSession([
                    'community' => $event->community_id
                ]);
                $namespace = Documenti::className();
                $bodyParams = [
                    'namespace' => $namespace,
                    'offset' => $offset,
                    'limit' => $limit
                ];
                $list = DocumentiParser::getItems($namespace,
                    $bodyParams);
                $cwh->setCwhScopeInSession($old_scope);
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return $list;
    }

    /**
     *
     * @param type $event_id
     * @param type $offset
     * @param type $limit
     * @return type
     */
    public function actionEventSondaggiList($event_id, $offset = null,
                                            $limit = null)
    {
        $list = [];
        try {
            $event = Event::findOne(['id' => $event_id]);
            if (!is_null($event)) {
                $cwh = $this->loadCwh();
                $old_scope = $cwh->getCwhScope();
                $cwh->setCwhScopeInSession([
                    'community' => $event->community_id
                ]);
                $params = [];
                $search = new SondaggiSearch();
                if (!is_null($offset)) {
                    $params['offest'] = $offset;
                }
                if (!is_null($limit)) {
                    $params['limit'] = $limit;
                }

                $dataProvider = $search->searchOwnInterest($params);
                $listModel = $dataProvider->getModels();
                foreach ($listModel as $model) {
                    $list[] = SondaggiParser::parseItem($model);
                }
                $cwh->setCwhScopeInSession($old_scope);
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return $list;
    }

    /**
     *
     * @return type
     * @throws Exception
     */
    private function loadCwh()
    {
        $cwh = null;

        $cwh = Yii::$app->getModule('cwh');
        if (is_null($cwh)) {
            throw new \Exception('CWH not present');
        }

        return $cwh;
    }

    /**
     *
     * @param integer $event_id
     * @return typepartecipants
     */
    public function actionEventPartecipantsList($event_id)
    {
        $list = [];
        try {
            $event = Event::findOne(['id' => $event_id]);
            if (!is_null($event)) {
                $partecipants = $event->communityUserMm;
                foreach ($partecipants as $partecipant) {
                    $list[] = PartecipantsParser::parseItem($partecipant);
                }
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return $list;
    }

    /**
     *
     * @param type $event_id
     * @param type $participant_id
     * @param type $companion_id
     */
    public function actionEventQrCode($event_id, $user_id)
    {
        try {
            return $this->actionEventTicket($event_id, $user_id);
            $event = Event::findOne(['id' => $event_id]);
            if (!is_null($event)) {
                $partecipantsQuery = $event->getCommunityUserMm();
                $partecipantsQuery->andWhere(['user_id' => $user_id]);
                $partecipant = $partecipantsQuery->one();
                if (!is_null($partecipant)) {
                    $invitation = EventInvitation::findOne(['event_id' => $event_id,
                        'user_id' => $user_id]);
                    if ($invitation) {
                        return \QRcode::svg(Url::base(true) . Url::toRoute(['register-participant',
                                'eid' => $event_id, 'pid' => $user_id, 'iid' => $invitation->id]),
                            "qrcode", false, QR_ECLEVEL_H, 200);
                    }
                }
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }

        return null;
    }

    /**
     *
     * @param type $event_id
     * @return type
     */
    public function actionEventUserRole($event_id)
    {
        try {
            $event = Event::findOne(['id' => $event_id]);
            if (!is_null($event)) {
                $partecipantsQuery = $event->getCommunityUserMm();
                $partecipantsQuery->andWhere(['user_id' => \Yii::$app->getUser()->id]);
                $partecipant = $partecipantsQuery->one();
                if (!is_null($partecipant)) {
                    return ['role' => $partecipant->role];
                }
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }

        return null;
    }

    /**
     * @param null $event_id
     * @param null $user_id
     * @return null|string
     */
    public function actionEventTicket($event_id = null, $user_id = null)
    {

        ///------------------------------
        try {
            /* $url = \Yii::$app->params['platform']['backendUrl'].'/img/dem_app_v2.jpg';
              return "<div><img style='max-width: 100%' src='".$url ."'> </div>"; */
            $file_jpg = EventsUtility::createDownloadTicket($event_id, $user_id, true);
            $invitation = EventInvitation::find()->andWhere(['event_id' => $event_id, 'user_id' => $user_id])->one();
            if ($invitation) {
                $file_jpg = $invitation->getTicketImage()->getWebUrl();
            }
            if (!empty($file_jpg)) {
                return "<div><img style='max-width: 100%' src='" . \Yii::$app->params['platform']['backendUrl'] . $file_jpg . "' /></div>";
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return null;
    }


    /**
     * @param $event_id
     * @return bool
     */
    public function actionEventHasTicket($event_id)
    {
        return EventUtility::EventHasTicket($event_id);
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdateCompanions()
    {
        $user_id = \Yii::$app->request->post('user_id');
        $event_id = \Yii::$app->request->post('event_id');
        $enable_companions = \Yii::$app->request->post('enable_companions');
        $n_companions = \Yii::$app->request->post('n_companions');
        $isOk = false;

        $event = Event::findOne($event_id);
        $invitation = EventInvitation::find()->andWhere(['user_id' => $user_id, 'event_id' => $event_id])->one();
        if ($invitation) {
            $eventControllers = new \open20\amos\events\controllers\EventController('event', \Yii::$app->getModule('events'));
            EventParticipantCompanion::deleteAll(['event_invitation_id' => $invitation->id]);
            $companions = $invitation->generateCompanions($n_companions);
            foreach ($companions as $companion) {
                $eventControllers->addCompanion($event->id, $invitation, $companion);
            }
            $isOk = true;
        } else {
            $isOk = false;
            $errors = \Yii::t('site', "L'utente non è iscritto all'evento");
            return [
                'isOk' => $isOk,
                'errors' => $errors
            ];
        }

        return [
            'isOk' => $isOk,
        ];
    }

    /**
     * @param $event_id
     * @param $user_id
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionSelectAvailableCompanions($event_id, $user_id)
    {
        $model = Event::findOne($event_id);
        $nCompanions = 0;
        /** @var  $invitation EventInvitation */
        $invitation = EventInvitation::find()->andWhere(['user_id' => $user_id, 'event_id' => $event_id])->one();
        if ($invitation) {
            $nCompanions = $invitation->getCompanions()->andWhere(['event_participant_companion.deleted_at' => null])->count();
        }


        $dataCompanions = $model->getListNcompanions();
        if (count($dataCompanions) < $nCompanions) {
            $dataCompanions = [];
            $list = range(1, $nCompanions);
            foreach ($list as $n) {
                $dataCompanions[$n] = $n;
            }
        }
        return [$dataCompanions];
    }

    /**
     * @param null $user_id
     * @return array|false[]
     * @throws \yii\base\InvalidConfigException
     */
    public function actionDeleteAccount($user_id = null)
    {
        if (empty($user_id)) {
            $user_id = \Yii::$app->user->id;
        }
        $userProfile = UserProfile::find()->andWhere(['user_id' => $user_id])->one();
        if ($userProfile) {
            $ok = UserProfileMailUtility::sendEmailDropAccountRequest($userProfile);
            return [
                'isOk' => $ok
            ];

        }
        return [
            'isOk' => false
        ];

    }

    public function actionHistoryPushNotification($user_id = null)
    {
        $history = [];
        if (empty($user_id)) {
            $user_id = \Yii::$app->user->id;
        }
        $pushNotificationsSent = \open20\amos\events\models\EventPushNotificationSent::find()
            ->andWhere(['user_id' => $user_id])
            ->groupBy('event_push_notification_id')
            ->orderBy('event_push_notification_sent.created_at DESC')
            ->limit(10)
            ->all();

        /** @var  $pushSent */
        foreach ($pushNotificationsSent as $pushSent) {
            $notificationPush = $pushSent->eventPushNotification;
            if ($notificationPush) {
//                print_r($notificationPush->attributes);
                $event = $notificationPush->event;
                $class = $notificationPush->content_class;
                $id = $notificationPush->content_id;
                $object = $class::findOne($id);

                if ($event && $object) {
//                    print_r("--------");
//                    print_r($event->id."-".$class);
//                    print_r('-'.$object->id);
//                    print_r("--------");

                    $texts = $this->getTexts($notificationPush, $event, $object);
                    $history[] = [
                        'title' => $texts['title'],
                        'text' => $texts['text'],
                        'date' => $notificationPush->created_at,
                        'event_id' => $event->id
                    ];
                }
            }
        }
        return $history;
    }

    /**
     * @param $push
     * @param $event
     * @param $object
     * @return array
     */
    public function getTexts($push, $event, $object)
    {
        $typesOfNotification = [EventPushNotification::TYPE_SAVE_THE_DATE, EventPushNotification::TYPE_INVITE_REGISTER];
        if (in_array($push->type, $typesOfNotification)) {
            if ($push->type == EventPushNotification::TYPE_SAVE_THE_DATE) {
                $title = AmosEvents::t('amosevents', $push->getTitlePushNotification());
                $text = AmosEvents::t('amosevents', $push->getTextPushNotification(), [
                    'title' => $event->title
                ]);
            } else if ($push->type == EventPushNotification::TYPE_INVITE_REGISTER) {
                $title = AmosEvents::t('amosevents', $push->getTitlePushNotification());
                $text = AmosEvents::t('amosevents', $push->getTextPushNotification(), [
                    'title' => $event->title
                ]);
            }


            // PUBLICATION CONTENTS (NEWS / DISCUSSIONI / DOCUMENTI /SONDAGGI )
        } else if ($push->type == EventPushNotification::TYPE_NEW_CONTENT) {
            $community = $event->community;
            if ($community) {
                $grammar = $object->getGrammar();
                $title = AmosEvents::t('amosevents', "Pubblicazione di")
                    . ' '
                    . $grammar->getIndefiniteArticle()
                    . ' '
                    . $grammar->getModelSingularLabel();

                $text = AmosEvents::t('amosevents', "Dai un'occhiata a {title}", [
                    'title' => $object->getTitle()
                ]);
            }

            // COMMUNICATIONS - EVENT DATA/PLACE CHANGE
        } else if ($push->type == EventPushNotification::TYPE_EVENT_CHANGED) {
            $title = AmosEvents::t('amosevents', $push->getTitlePushNotification());
            $text = AmosEvents::t('amosevents', $push->getTextPushNotification(), [
                'TITOLO' => $event->title,
                'DATA_INIZIO' => date('d/m/Y', strtotime($event->begin_date_hour)),
                'DATA_FINE' => date('d/m/Y', strtotime($event->end_date_hour)),
                'ORA_INIZIO' => date('H:i', strtotime($event->begin_date_hour)),
                'ORA_FINE' => date('H:i', strtotime($event->end_date_hour)),
                'LOCATION' => $event->eventLocation->name,
                'INDIRIZZO' => $event->eventLocationEntrance->name,
            ]);
        }
        return ['title' => $title, 'text' => $text];
    }

    /**
     * @return array|string[]
     */
    public function actionGetLastAppVersion()
    {
        $ret = ['status' => 'ok', 'message' => ''];
        try {
            /** @var ActiveQuery $query */
            $query = AppVersion::find();
            $av = $query->orderBy(['version' => SORT_DESC])->one();
            if (!empty($av)){
                $ret['data'] = $av->attributes;
            } else {
                $ret = ['status' => 'error', 'message' => 'nessuna versione impostata'];
            }
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
            $ret = ['status' => 'error', 'message' => $ex->getMessage()];
        }
        return $ret;
    }
}