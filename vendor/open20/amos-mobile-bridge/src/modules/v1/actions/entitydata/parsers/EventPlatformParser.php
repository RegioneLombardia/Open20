<?php

namespace open20\amos\mobile\bridge\modules\v1\actions\entitydata\parsers;

use open20\amos\admin\models\UserProfile;
use open20\amos\events\models\Event;
use open20\amos\events\utility\EventsUtility;
use open20\amos\tag\models\Tag;
use Yii;
use yii\helpers\StringHelper;

class EventPlatformParser extends BaseParser
{

    /**
     *
     * @param Event $item
     * @return array
     */
    public static function parseItem($item, $isDetail = false)
    {
        //The base class name
        $baseClassName = StringHelper::basename(Event::className());

        //Read permission name
        $readPremission = strtoupper($baseClassName . '_READ');

        //Edit permission name
        $editPremission = strtoupper($baseClassName . '_UPDATE');

        //Can user view element
        //$canView = Yii::$app->user->can($readPremission, ['model' => $item]);

        //if ($canView) {
        $mapPosition = [];
        $eventLocation = $item->eventLocation;
        $eventEntrance = $item->eventLocationEntrance;
        $eventPlace = $eventLocation->eventPlaces;
        if($eventPlace){
            $mapPosition = ['lat' => $eventPlace->latitude, 'lng' => $eventPlace->longitude];
        }

        $eventType = $item->eventType;
        $highlight = \open20\amos\events\models\EventHighlights::find()->andWhere(['event_id' => $item->id])->orderBy('id DESC')->one();
        $highlights = 0;
        $relatedEvents = [];
        if ($highlight) {
            $highlights = $highlight->n_order;
        }
        $tagsPreference = self::getPreferenceTags($item);

        $isWebmeeting = false;
        $webmeetingConfig = [];
        if ($eventType->webmeeting_webex) {
            $isWebmeeting = true;
            $webmeetingConfig = self::getWebmeetingConfig($item);
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
        $imageUrl = $item->getMainImageEvent();
        $url = $imageUrl;

        if (strpos($imageUrl, 'https') === false) {
//                pr('dentro');
            $url = Yii::$app->getUrlManager()->createAbsoluteUrl($imageUrl);
        }



        //Fill fields from item usable in app
        $newItem['fields'] = [
            'begin_date_hour' => $item->begin_date_hour,
            'end_date_hour' => $item->end_date_hour,
            'title' => self::flushHtml($item->title),
            'description' => self::flushHtml($item->description),
            'summary' => self::flushHtml($item->summary),
            'map_position' => $mapPosition,
            'event_location' => $eventLocation->name,
            'event_entrance' => $eventEntrance->name,
            'event_address' => $eventPlace->address,
            'event_address_house_number' => $eventPlace->street_number,
            'event_address_cap' => $eventPlace->postal_code,
            'city' => $eventPlace->city,
            'province' => $eventPlace->province,
            'country' => $eventPlace->country,
            'created_at' => $item->created_at,
            'created_by' => $item->created_by,
            'comments_enabled' => true,
            'show_community' => $item->show_community,
            'event_type_id' => $eventType->id,
            'isWebmeeting' => $isWebmeeting,
            'webmeeting_config' => $webmeetingConfig,
            'informative_tags' => $tagsPreference,
            'multilanguage' => $item->multilanguage,
            'seats_management' => $item->seats_management,
            'enable_companions' => $item->enable_companions,
            'numero_max_accompagnatori' => $item->numero_max_accompagnatori,
            'has_tickets' => $item->has_tickets,
            'user_has_ticket' => \open20\amos\mobile\bridge\modules\v1\utility\EventUtility::EventHasTicket($item->id),
            'has_qr_code' => $item->has_qr_code,
            'highlights' => $highlights,
            'owner' => [
                'nome' => $owner->nome,
                'cognome' => $owner->cognome,
                'presentazione_breve' => $owner->presentazione_breve,
                'avatarUrl' => $owner->avatarWebUrl,
            ],
            'eventImageUrl' => $url ? $url : null,
            'landingUrl' => \open20\amos\events\utility\EventsUtility::getUrlLanding($item),

        ];
        $newItem['likeMe'] = self::isLikeMe($item);
        $newItem['countLikeMe'] = self::getCountLike($item);
        //Remove id as is not needed
        if(isset($newItem['fields']['id'])){
                unset($newItem['fields']['id']);
            }

        //Can edit
        $newItem['canEdit'] = Yii::$app->user->can($editPremission, ['model' => $item]);

        // DETTAGLIO EVENTI
        if($isDetail){
            // eventi correlati

            $newItem['fields']['correlatedEvents'] = self::getRelatedEvents($item);
        }

        return $newItem;
        //}

        //return [];
    }

    /**
     * @param $item
     * @return mixed
     */
    public static function getRelatedEvents($item){
        $events = [];
        if(method_exists($item, 'getCorrelatedEvents')){
            $relatedEvents = $item->correlatedEvents;
            foreach ($relatedEvents as $event) {
                $imageUrl = $event->getMainImageEvent();
                $url = $imageUrl;
                if (strpos($imageUrl, 'https') === false) {
//                pr('dentro');
                    $url = Yii::$app->getUrlManager()->createAbsoluteUrl($imageUrl);
                }
                $events [] = [
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'begin_date_hour' => $event->begin_date_hour,
                    'end_date_hour' => $event->end_date_hour,
                    'eventImageUrl' => $url ?: null,
                    'landingUrl' => \open20\amos\events\utility\EventsUtility::getUrlLanding($event),
                ];
            }
        }
        return $events;
    }

    public static function getWebmeetingConfig($model)
    {
        $webmeeting = $model->webMeetingWebex;
        $config = [];
        if ($webmeeting) {
            $closed = $model->webexIsClosed();
            $canGo = $model->canGoToWebexUrl();
            if ($closed) {
                $config ['web_link'] = null;
                $config ['status'] = 'closed';
            } else {
                if ($canGo) {
                    $config ['web_link'] = $webmeeting->web_link;
                    $config ['status'] = 'active';
                } else {
                    $config ['web_link'] = null;
                    $config ['status'] = 'not_started';
                }
            }
            $config ['start_date'] = $webmeeting->start;
            $config ['end_date'] = $webmeeting->end;
        }
        return $config;
    }


    /***
     * @param $event
     * @return array
     */
    public static function eventChildren($event)
    {
        $childrens = [];
        $eventChildren = $event->getEventChildren()
            ->andWhere(['event.status' => Event::EVENTS_WORKFLOW_STATUS_PUBLISHED])
            ->all();
        foreach ($eventChildren as $child) {
            $imageUrl = $child->getMainImageEvent();
            $url = $imageUrl;
            if (strpos($imageUrl, 'https') === false) {
                $url = Yii::$app->getUrlManager()->createAbsoluteUrl($imageUrl);
            }
            $childrens[] = [
                'id' => $child->id,
                'title' => $child->title,
                'description' => $child->description,
                'begin_date_hour' => $child->begin_date_hour,
                'end_date_hour' => $child->end_date_hour,
                'eventImageUrl' => $url ? $url : null,
                'urlLanding' => EventsUtility::getUrlLanding($child)
            ];
        }
        return $childrens;

    }

    /**
     * @param $event
     * @return array
     */
    public static function getPreferenceTags($event)
    {
        $root = Tag::find()->andWhere(['codice' => Event::ROOT_TAG_PREFERENCE_CENTER])->one();
        $preferenceTags = [];
        $tags = Tag::find()
            ->innerJoin('entitys_tags_mm', 'entitys_tags_mm.tag_id = tag.id')
            ->andWhere(['root_id' => $root->id])
            ->andWhere(['record_id' => $event->id])
            ->all();
        foreach ($tags as $tag) {
            $preferenceTags [] = ['id' => $tag->id, 'name' => $tag->nome, 'codice' => $tag->codice];
        }
        return $preferenceTags;
    }
}
