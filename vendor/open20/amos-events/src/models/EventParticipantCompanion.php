<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\models
 * @category   Model
 */

namespace open20\amos\events\models;

/**
 * Class EventParticipantCompanion
 *
 * This is the model class for table "event_participant_companion".
 *
 * @property integer $event_id
 * @property \open20\amos\events\models\Event $event
 * @property-read string $nomeCognome
 * @property-read string $userEmail
 *
 * @package open20\amos\events\models
 */
class EventParticipantCompanion extends \open20\amos\events\models\base\EventParticipantCompanion
{
    public $event_id;

    /**
     * @return Event|null
     */
    public function getEvent()
    {
        /** @var Event $eventModel */
        $eventModel = $this->eventsModule->createModel('Event');
        return $eventModel::findOne(['id' => $this->event_id]);
    }

    /**
     * This method returns the name and surname of the user invited.
     * If the invitation is linked to an user, the method returns the name and surname of the user profile.
     * @return string
     */
    public function getNomeCognome()
    {
        $user = $this->user;
        if (!is_null($user)) {
            $nomeCognome = $user->userProfile->nomeCognome;
        } else {
            $nomeCognome = $this->nome . ' ' . $this->cognome;
        }
        return $nomeCognome;
    }

    /**
     * This method returns the email of the companion invited.
     * If the companion is linked to an user, the method returns the email of the user profile.
     * @return string
     */
    public function getUserEmail()
    {
        $user = $this->user;
        if (!is_null($user)) {
            $email = $user->email;
        } else {
            $email = $this->email;
        }
        return $email;
    }
}
