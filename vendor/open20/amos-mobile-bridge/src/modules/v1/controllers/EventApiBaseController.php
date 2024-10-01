<?php

namespace open20\amos\mobile\bridge\modules\v1\controllers;

use open20\amos\admin\models\UserProfile;
use open20\amos\core\helpers\StringHelper;
use open20\amos\discussioni\models\DiscussioniTopic;
use open20\amos\documenti\models\Documenti;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventInvitation;
use open20\amos\events\models\EventParticipantCompanion;
use open20\amos\events\models\search\EventSearch;
use open20\amos\events\utility\EventsUtility;
use open20\amos\mobile\bridge\modules\v1\actions\entitydata\parsers\EventPlatformParser;
use open20\amos\news\models\News;
use open20\amos\sondaggi\models\search\SondaggiSearch;
use open20\amos\tag\models\Tag;
use Exception;
use kartik\mpdf\Pdf;
use Yii;
use yii\db\Expression;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\log\Logger;
use yii\rest\Controller;

class EventApiBaseController extends DefaultController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviours = parent::behaviors();

        return ArrayHelper::merge($behaviours,
            [
                'verbFilter' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'event-detail' => ['get']
                    ],
                ],
            ]);
    }

    /**
     * @param $action
     * @return mixed
     */
    public function beforeAction($action)
    {
        if (\Yii::$app->request->get('language')) {
            \Yii::$app->language = \Yii::$app->request->get('language');
        } else {
            \Yii::$app->language = 'it-IT';
        }

        return parent::beforeAction($action);

    }

    /**
     * @param $event_id
     * @return array
     */
    public function actionEventDetail($event_id)
    {
        $detail = [];

        try {
            $detail = self::baseParseItem(Event::findOne(['id' => $event_id]));
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return $detail;
    }

    /**
     * Get all active events list (in progress events)
     * @return array
     */
    public function getBaseEventsList()
    {
        $eventsModule = AmosEvents::instance();
        $eventTypeModel = $eventsModule->model('EventType');
        $params = [];
        $search = new EventSearch();
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
            ->andWhere([Event::tableName() . '.status' => Event::EVENTS_WORKFLOW_STATUS_PUBLISHED])
            ->andWhere(['<=', 'publication_date_begin', $now])
            ->andWhere(['or',
                    ['>=', 'publication_date_end', $now],
                    ['publication_date_end' => null]]
            )
            ->andWhere(['or',
                    ['>=', 'end_date_hour', $now],
                    ['end_date_hour' => null]]
            );

        return [
            'query' => $query,
            'dataProvider' => $dataProvider
        ];
    }

    public function getCurrentPageNumberOfElements($list)
    {
        $eventsInPage = 0;
        foreach ($list as $item) {
            $eventsInPage++;
        }

        return $eventsInPage;
    }

    /**
     * @param $item Event
     * @return array
     */
    public function baseParseItem($item)
    {
        //The base class name
//        $baseClassName = StringHelper::basename(Event::className());

        //Read permission name
//        $readPremission = strtoupper($baseClassName . '_READ');

        //Edit permission name
//        $editPremission = strtoupper($baseClassName . '_UPDATE');


        $eventLocation = $item->eventLocation;
        $eventEntrance = $item->eventLocationEntrance;

        $eventType = $item->eventType;
        $highlight = \open20\amos\events\models\EventHighlights::find()->andWhere(['event_id' => $item->id])->orderBy('id DESC')->one();
        $highlights = 0;
        if ($highlight) {
            $highlights = $highlight->n_order;
        }
        $tagsPreference = EventPlatformParser::getPreferenceTags($item);

        $isWebmeeting = false;
        if ($eventType->webmeeting_webex) {
            $isWebmeeting = true;
        }

        //Define temp item
        $newItem = [];

        //Need id column
        $newItem['id'] = $item->id;

        //Get the list of description fields
        $newItem['representingColumn'] = $item->representingColumn();

        //Creator profile
//        $owner = UserProfile::findOne(['id' => $item->created_by]);

        //Image
//        $image = $item->eventLogo;
        $imageUrl = $item->getMainImageEvent();
        $url = $imageUrl;

        if (strpos($imageUrl, 'https') === false) {
            $url = Yii::$app->getUrlManager()->createAbsoluteUrl($imageUrl);
        }

        //Set language
        setlocale(LC_ALL, 'it_IT');

        $beginDate = date_create($item->begin_date_hour);
        $beginDateFormatted = date_format(date_create($item->begin_date_hour), 'Y-m-d H:i:s');

        //Fill fields from item usable in app
        $newItem['fields'] = [
            'title' => $item->title,
            'event_type_id' => [
                'id' => $eventType->id,
                'name' => $eventType->title
            ],
            'description' => $item->description,
            'event_location' => $eventLocation->name,
            'event_entrance' => $eventEntrance->name,
            'multilanguage' => $item->multilanguage,
            'highlights' => $highlights,
            'show_community' => $item->show_community,
            'isWebmeeting' => $isWebmeeting,
            'informative_tags' => $tagsPreference,
            'begin_date' => [
                'full_date' => $beginDate,
                'day' => date_format($beginDate, "d"),
                'month' => utf8_encode(ucwords(strftime('%b', strtotime($beginDateFormatted)))),
                'year' => date_format($beginDate, "Y"),
                'hour' => date_format($beginDate, 'H:i')
            ],
            'end_date_hour' => $item->end_date_hour,
            'registration_date_begin' => $item->registration_date_begin,
            'registration_date_end' => $item->registration_date_end,
            'eventImageUrl' => $url ? $url : null,
            'status' => $item->status,
            'landingUrl' => \open20\amos\events\utility\EventsUtility::getUrlLanding($item),
            'isChild' => !empty($item->event_id) ? true : false,
            'isFather' => $item->is_father,
        ];

        //Remove id as is not needed
        if (isset($newItem['fields']['id'])) {
            unset($newItem['fields']['id']);
        }

        return $newItem;

    }

}