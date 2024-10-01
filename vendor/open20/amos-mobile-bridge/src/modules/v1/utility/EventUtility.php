<?php
namespace open20\amos\mobile\bridge\modules\v1\utility;

use open20\amos\events\models\Event;
use open20\amos\events\models\EventInvitation;
use luya\web\filters\ResponseCache;
use Yii;

class EventUtility
{

    public static function log($text, $filename = 'debug.txt'){
        $myfile = fopen($filename, "a") or die("Unable to open file!");
        $txt = date('d/m/Y H:i:s').' - '. "$text\n";
        fwrite($myfile, $txt);
        fclose($myfile);
    }
    /**
     *
     * @param integer $event_id
     * @return boolean
     */
    public static function EventHasTicket($event_id)
    {
        $ret = false;
        $eventModule = Yii::$app->getModule('events');
        if (!is_null($eventModule)) {

            /** @var Event $event */
            $event = Event::findOne(['id' => $event_id]);
            if (!is_null($event)) {
                if ($event->has_tickets) {
                    /** @var EventInvitation $invitation */
                    $invitation = EventInvitation::findOne(['event_id' => $event_id, 'user_id' => Yii::$app->user->id]);
                    if (!is_null($invitation)) {
                        $ret = true;
                    }
                }
            }
        }
        return $ret;
    }

    /**
     * @param $dependencySql
     * @return array
     */
    public static function mobileCacheConfigs($dependencySql = null){
        $pagesWithEnableCache = [
            'event-platform/events-list-home',
            'event-platform/event-detail',
            'event-platform/events-list',
            'event-platform/event-news-list',
            'event-platform/event-documenti-list',

            'events-aria/events-list',
            'events-aria/event-detail',
            'events-aria/events-search',
//            'events-aria/form-fields',
            'events-aria/preference-tags',
            'events-aria/cities-from-country',
            'events-aria/countries',
            'events-aria/states',
            'events-aria/event-children',

            'event-giovani/events-list',
            'event-giovani/event-detail',
        ];
        $actionDependingFromUser = [
           // 'events-aria/form-fields',
        ];

        $url = \Yii::$app->controller->id. '/'.\Yii::$app->controller->action->id;
        $sql = 'SELECT GREATEST(max(event.updated_at), max(event_landing.updated_at)) FROM event inner join event_landing ON event_landing.event_id = event.id';
        if(!empty($dependencySql)){
            $sql = $dependencySql;
        }

        //le action che dipendono dall'utente devono avere anche l'utente come variazione
        return [
            'class' => ResponseCache::class,
            'variations' => [
                Yii::$app->request->url,
                Yii::$app->language,
                Yii::$app->request->get(),
                Yii::$app->request->post(),
                Yii::$app->user->isGuest && !in_array($url, $actionDependingFromUser)? 'guest' : \Yii::$app->user->id
            ],
            'cache' => 'mobileCache',
            'duration' => 7200,
            'dependency' => [
                'class' => 'yii\caching\DbDependency',
                'sql' => $sql
            ],
            'enabled' => in_array($url, $pagesWithEnableCache),
        ];
    }
}