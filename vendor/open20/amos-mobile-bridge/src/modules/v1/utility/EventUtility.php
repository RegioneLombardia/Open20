<?php
namespace open20\amos\mobile\bridge\modules\v1\utility;

use open20\amos\events\models\Event;
use open20\amos\events\models\EventInvitation;
use Yii;

class EventUtility
{

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
}