<?php

namespace open20\amos\events\models;

use yii\helpers\ArrayHelper;

class EventParticipantCompanionDynamic extends EventParticipantCompanion
{
    public $event_id;

    /**
     * @return Event|null
     */
    public function getEvent() {
        /** @var Event $eventModel */
        $eventModel = $this->eventsModule->createModel('Event');
        return $eventModel::findOne(['id' => $this->event_id]);
    }
}
