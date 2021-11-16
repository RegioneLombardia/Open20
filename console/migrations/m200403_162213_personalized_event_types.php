<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m200403_162213_personalized_event_types extends Migration
{



    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        ;

        $this->update('event_type', ['title' => "Su invito (evento privato)", "description" => "Evento nascosto, solo gli invitati hanno l’accesso alla scheda dell'evento,solo gli invitati si possono registrare dal link in email che riporta un token sull'id dell'utente, limitazione numero di posti, gestione delle liste d’attesa con relative comunicazioni", 'deleted_by' => null, 'deleted_at' => null, 'limited_seats' => false,], ['id' => \open20\amos\events\models\EventType::TYPE_UPON_INVITATION]);
        $this->update('event_type', ['title' => "Evento informativo", 'description' => 'Tutti possono vederlo, non è richiesta registrazione ma solo una email per inviare le informazioni specifiche.'], ['id' => \open20\amos\events\models\EventType::TYPE_INFORMATIVE]);
        $this->update('event_type', ['title' => "Su invito (evento privato) con posti limitati", 'description' => 'Solo gli invitati possono registrarsi, con limite di posti e liste di attesa opzionali, limitazione numero di posti, gestione delle liste d’attesa con relative comunicazioni'], ['id' => \open20\amos\events\models\EventType::TYPE_LIMITED_SEATS]);
        $this->update('event_type', ['limited_seats' => true, 'title' => "Evento pubblico (aperto a tutti) con posti limitati", 'description' => 'Tutti possono verderlo, la registrazione è obbligatoria e aperta a tutti, con limite di posti e liste di attesa opzionali'], ['id' => \open20\amos\events\models\EventType::TYPE_OPEN]);

        $this->insert('event_type', [
            'title' => "Evento pubblico (aperto a tutti)",
            "description" => "Tutti possono verderlo, la registrazione è obbligatoria e aperta a tutti" ,
            'limited_seats' => false,
            'partners' => false,
            "color" => "#CB740B",
            "enabled" => true,
            'event_type' => \open20\amos\events\models\EventType::TYPE_OPEN
        ]);

        $this->insert('event_type', [
            'title' => "Patrocinio",
            "description" => "Tutti possono vederlo, non è richiesta registrazione ma solo una email per inviare le informazioni specifiche." ,
            'limited_seats' => false,
            'partners' => false,
            "color" => "#737373",
            "enabled" => true,
            'event_type' => \open20\amos\events\models\EventType::TYPE_INFORMATIVE
        ]);

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {

    }
}
