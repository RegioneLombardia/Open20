<?php

namespace open20\amos\mobile\bridge\modules\v1\controllers;

use open20\amos\admin\models\UserProfile;
use open20\amos\core\helpers\StringHelper;
use open20\amos\core\record\CachedActiveQuery;
use open20\amos\discussioni\models\DiscussioniTopic;
use open20\amos\documenti\models\Documenti;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventInvitation;
use open20\amos\events\models\EventParticipantCompanion;
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
use Yii;
use yii\db\Expression;
use yii\db\Query;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\log\Logger;
use yii\rest\Controller;

class EventPreferenceController extends DefaultController
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
                        'events-list' => ['get'],
                        'event-detail' => ['get'],
                        'event-changes' => ['get'],
                        'send-notification' => ['post'],
                    ],
                ],
            ]);
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

    /**
     * @param null $offset
     * @param null $limit
     * @param null $from_date
     * @param null $to_date
     * @return array
     */
    public function actionEventsList($offset = null, $limit = null, $from_date = null, $to_date = null)
    {
        $list = [];
//        try {
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
            );


        $cachedQuery = CachedActiveQuery::instance($query);
        $cachedQuery->cache();
        $query->limit($limit);
//            pr($query->createCommand()->rawSql);
        $dataProvider->query = $query;
        $dataProvider->pagination = false;
        $listModel = $dataProvider->getModels();

        foreach ($listModel as $model) {
            $list[] = self::parseItem($model);
        }
//        } catch (Exception $ex) {
//            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
//        }
        return $list;
    }

    /**
     * @param $event_id
     * @return array
     */
    public function actionEventDetail($event_id)
    {
        $detail = [];

        try {
            $detail = self::parseItem(Event::findOne(['id' => $event_id]));
        } catch (Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return $detail;
    }

    /**
     * @param null $idFrom
     * @param null $dateFrom
     * @return array
     */
    public function actionEventChanges($idFrom = null, $dateFrom = null)
    {
        $result = [];
        $query = \open20\amos\events\models\EventChangeAttributes::find();

        if (!empty($idFrom)) {
            $query->andWhere(['>', 'id', $idFrom]);
        }
        if (!empty($dateFrom)) {
            $query->andWhere(['>=', new Expression('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i:%s")'), new Expression('DATE_FORMAT("' . $dateFrom . '", "%Y-%m-%d %H:%i:%s")')]);
        }

        $changes = $query->all();

        foreach ($changes as $change) {
            $newValue = $this->parseValue($change, $change->model_attribute, 'new_value');
            $oldValue = $this->parseValue($change, $change->model_attribute, 'old_value');
            $result [] = [
                'id' => $change->id,
                'event_id' => $change->event_id,
                'operation_type' => $change->operation_type,
                'attribute' => $change->model_attribute,
                'old_value' => $oldValue,
                'new_value' => $newValue,
                'created_at' => $change->created_at,
            ];
        }
        return $result;
    }


    /**
     * @param $model
     * @param $attribute
     * @param $oldNewAttribute
     * @return mixed
     */
    public function parseValue($model, $attribute, $oldNewAttribute)
    {
        $value = $model->$oldNewAttribute;
        try {
            if ($attribute == 'event_location_id') {
                $tmpVal = '';
                $unserializedVal = unserialize($value);

                if (!empty($unserializedVal['type']) && $unserializedVal['type'] == 'label') {
                    $tmpVal .= $unserializedVal['event_location_id'];
                    $tmpVal .= '|||' .$unserializedVal['event_location_entrance_id'];
                } else {
                    $location = \open20\amos\events\models\EventLocation::findOne($unserializedVal['event_location_id']);
                    $entrance = \open20\amos\events\models\EventLocationEntrances::findOne($unserializedVal['event_location_entrance_id']);
                    if ($location) {
                        $tmpVal .= $location->name;
                    }
                    if ($entrance) {
                        $tmpVal .= '|||' . $entrance->name;
                    }
                }


                $value = $tmpVal;
            }
        } catch (Exception $e) {
        }
        return $value;
    }


    /**
     * @param $item Event
     * @return array
     */
    public static function parseItem($item)
    {
        //The base class name
        $baseClassName = StringHelper::basename(Event::className());

        //Read permission name
        $readPremission = strtoupper($baseClassName . '_READ');

        //Edit permission name
        $editPremission = strtoupper($baseClassName . '_UPDATE');


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
        $owner = UserProfile::findOne(['id' => $item->created_by]);

        //Image
        $image = $item->eventLogo;
        $imageUrl = $item->getMainImageEvent();
        $url = $imageUrl;

        if (strpos($imageUrl, 'https') === false) {
            $url = Yii::$app->getUrlManager()->createAbsoluteUrl($imageUrl);
        }

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
            'begin_date_hour' => $item->begin_date_hour,
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
        //}

        //return [];
    }


    /***
     * @param $event
     * @return array
     */
    public static function countEventChildren($event)
    {
        $countEventChildren = $event->getEventChildren()->count();
        return $countEventChildren;
    }


    /**
     * @return bool
     */
    public function actionSendNotification()
    {
        $post = \Yii::$app->request->post();
        if ($post['notification_type'] == 1) {
            $ok = \open20\amos\events\utility\EventMailUtility::sendEmailNotifyEventPreference($post['event_id'], $post);
            return $ok;
        } else if ($post['notification_type'] == 2) {
            $ok = \open20\amos\events\utility\EventMailUtility::sendEmailNotifyCampainPreference($post['event_id'], $post);
            return $ok;
        }
        return false;
    }

}