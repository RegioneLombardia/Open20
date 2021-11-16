<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200330_124413_personalized_event_types extends Migration
{



    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        ;

        $this->update('event_type', ['deleted_by' => 1, 'deleted_at' => '2020-03-30 12:00:00'], ['id' => \open20\amos\events\models\EventType::TYPE_UPON_INVITATION]);
        $this->update('event_type', ['title' => "Pubblico senza registrazione", 'description' => 'Tutti possono vederlo, non è richiesta registrazione ma solo una email per inviare le informazioni specifiche.'], ['id' => \open20\amos\events\models\EventType::TYPE_INFORMATIVE]);
        $this->update('event_type', ['title' => "Privato", 'description' => 'Solo gli invitati possono registrarsi, con limite di posti e liste di attesa opzionali'], ['id' => \open20\amos\events\models\EventType::TYPE_LIMITED_SEATS]);
        $this->update('event_type', ['limited_seats' => true, 'manage_subscritions_queue' => true, 'title' => "Pubblico con registrazione", 'description' => 'Tutti possono verderlo, la registrazione è obbligatoria e aperta a tutti, con limite di posti e liste di attesa opzionali'], ['id' => \open20\amos\events\models\EventType::TYPE_OPEN]);


    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->update('event_type', ['deleted_by' => null, 'deleted_at' => null], ['id' => \open20\amos\events\models\EventType::TYPE_UPON_INVITATION]);
        $this->update('event_type', ['title' => "Evento informatico", 'description' => 'Evento informativo'], ['id' => \open20\amos\events\models\EventType::TYPE_INFORMATIVE]);
        $this->update('event_type', ['title' => "Evento su invito", 'description' => 'Evento su invito'], ['id' => \open20\amos\events\models\EventType::TYPE_LIMITED_SEATS]);
        $this->update('event_type', ['limited_seats' => false, 'manage_subscritions_queue' => false, 'title' => "Evento aperto", 'description' => 'Evento aperto'], ['id' => \open20\amos\events\models\EventType::TYPE_OPEN]);



    }
}
