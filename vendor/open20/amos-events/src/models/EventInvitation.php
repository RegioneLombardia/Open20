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
 * @property-read string $completeAddress
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

    public static function uuid4()
    {
	    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
	        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
	        mt_rand( 0, 0xffff ),
	        mt_rand( 0, 0x0fff ) | 0x4000,
	        mt_rand( 0, 0x3fff ) | 0x8000,
	        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
	    );
	}

    public function everyoneInSameInvitationHasAccreditationList()
    {
        $result = !empty($this->accreditation_list_id);

        if($result) {
            $companions = $this->getCompanions();
            if(!empty($companions)) {
                foreach ($companions->all() as $companion) {
                    if($result) {
                        $result = !empty($companion->event_accreditation_list_id);
                    } else { break; }
                }
            }
        }

        return $result;
    }

    public function getCompanions()
    {
        return $this->hasMany($this->eventsModule->model('EventParticipantCompanion'), ['event_invitation_id' => 'id']);
    }

    /**
     * @return EventSeats
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
}
