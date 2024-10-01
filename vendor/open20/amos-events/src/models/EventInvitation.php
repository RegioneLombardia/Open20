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
 * Class EventInvitation
 * This is the model class for table "event_invitation".
 *
 * @property-read string $nomeCognome
 * @property-read string $userEmail
 *
 * @package open20\amos\events\models
 */
class EventInvitation extends \open20\amos\events\models\base\EventInvitation
{
    const INVITATION_TYPE_REGISTERED = 1;
    const INVITATION_TYPE_IMPORTED = 2;

    const INVITATION_TYPE_REGISTERED_BY_PUBLIC_FORM = 40;

    const INVITATION_STATE_INVITED = 1;
    const INVITATION_STATE_ACCEPTED = 2;
    const INVITATION_STATE_REJECTED = 3;

    /**
     * @return string
     */
    public static function uuid4()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    /**
     * @return bool
     */
    public function everyoneInSameInvitationHasAccreditationList()
    {
        $result = !empty($this->accreditation_list_id);

        if ($result) {
            $companions = $this->getCompanions();
            if (!empty($companions)) {
                foreach ($companions->all() as $companion) {
                    if ($result) {
                        $result = !empty($companion->event_accreditation_list_id);
                    } else {
                        break;
                    }
                }
            }
        }

        return $result;
    }

    public function countGdprAnswers()
    {
        $count = 0;
        if ($this->eventsModule->enableGdpr) {
            if (!empty($this->gdpr_answer_1)) {
                $count++;
            }
            if (!empty($this->gdpr_answer_2)) {
                $count++;
            }
            if (!empty($this->gdpr_answer_3)) {
                $count++;
            }
            if (!empty($this->gdpr_answer_4)) {
                $count++;
            }
            if (!empty($this->gdpr_answer_5)) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * @return EventSeats|null
     * @throws \yii\base\InvalidConfigException
     */
    public function getAssignedSeat()
    {
        /** @var EventSeats $eventSeatsModel */
        $eventSeatsModel = $this->eventsModule->createModel('EventSeats');
        return $eventSeatsModel::find()
            ->andWhere(['event_id' => $this->event_id])
            ->andWhere(['user_id' => $this->user_id])
            ->one();
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
            $nomeCognome = $this->name . ' ' . $this->surname;
        }
        return $nomeCognome;
    }

    /**
     * This method returns the email of the user invited.
     * If the invitation is linked to an user, the method returns the email of the user profile.
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

    /**
     * This method returns the invitation notes truncated to 45 characters by default.
     * You can specify the truncate length by the "truncateCount" param.
     * @param int $truncateCount
     * @return string
     */
    public function getTruncatedNotes($truncateCount = 45)
    {
        return $this->__shortText($this->notes, $truncateCount);
    }
}
