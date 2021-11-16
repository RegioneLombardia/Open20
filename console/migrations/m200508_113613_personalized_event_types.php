<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200508_113613_personalized_event_types extends Migration
{



    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        $this->update('event_type',['event_context_id' => 1], [
            'title' => "Evento pubblico (aperto a tutti)",
            "description" => "Tutti possono verderlo, la registrazione è obbligatoria e aperta a tutti" ,
            "enabled" => true,
            'event_type' => \open20\amos\events\models\EventType::TYPE_OPEN
        ]);

        $this->update('event_type', ['event_context_id' => 1],[
            'title' => "Patrocinio",
            "description" => "Tutti possono vederlo, non è richiesta registrazione ma solo una email per inviare le informazioni specifiche." ,
            'limited_seats' => false,
            "enabled" => true,
            'event_type' => \open20\amos\events\models\EventType::TYPE_INFORMATIVE
        ]);

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        return true;

    }
}
