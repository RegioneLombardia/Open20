<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\models\base
 * @category   Model
 */

namespace open20\amos\events\models\base;

use open20\amos\events\AmosEvents;

/**
 * Class EventInvitation
 *
 * This is the model class for table "event_invitation".
 *
 * @property int $id
 * @property int $event_id
 * @property int $is_group
 * @property string $code
 * @property string $googlepay_ticket_id
 * @property string $apple_wallet_device_id
 * @property string $email
 * @property string $fiscal_code
 * @property string $name
 * @property string $surname
 * @property int $type Type of invited user - 1:registered, 2:imported
 * @property int $state State of invitation - 1:invited, 2:accepted, 3:rejected
 * @property string $invitation_sent_on
 * @property string $invitation_response_on
 * @property int $user_id This is the id of the user in the system
 * @property int $partner_of This is the main user who invited this one
 * @property int $accreditation_list_id
 * @property boolean $is_ticket_sent
 * @property string $ticket_downloaded_at
 * @property int $ticket_downloaded_by
 * @property boolean $presenza
 * @property string $presenza_scansionata_il
 * @property string $notes
 * @property string $company
 * @property int $gdpr_answer_1
 * @property int $gdpr_answer_2
 * @property int $gdpr_answer_3
 * @property int $gdpr_answer_4
 * @property int $gdpr_answer_5
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $deleted_by
 *
 * @property \open20\amos\events\models\Event $event
 * @property \open20\amos\core\user\User $user
 * @property \open20\amos\events\models\EventInvitation $partnerOf
 * @property \open20\amos\events\models\EventInvitation[] $eventInvitations
 * @property \open20\amos\events\models\EventAccreditationList[] $accreditationList
 * @property \open20\amos\events\models\EventParticipantCompanion[] $companions
 *
 * @package open20\amos\events\models\base
 */
class EventInvitation extends \open20\amos\core\record\Record
{
    /**
     * @var AmosEvents $eventsModule
     */
    public $eventsModule = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_invitation';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->eventsModule = AmosEvents::instance();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['event_id', 'email', 'fiscal_code', 'name', 'surname'], 'required'],
            [['event_id'], 'required'],
            [['event_id', 'type', 'state', 'user_id', 'partner_of', 'created_by', 'updated_by', 'deleted_by', 'accreditation_list_id', 'gdpr_answer_1', 'gdpr_answer_2', 'gdpr_answer_3', 'gdpr_answer_4', 'gdpr_answer_5'], 'integer'],
            [['presenza', 'is_group'], 'boolean'],
            [['apple_wallet_device_id', 'googlepay_ticket_id', 'code', 'invitation_sent_on', 'invitation_response_on', 'created_at', 'updated_at', 'deleted_at', 'presenza_scansionata_il', 'accreditation_list_id'], 'safe'],
            [['code'], 'string', 'max' => 36],
            [['notes'], 'string'],
            [['email', 'company'], 'string', 'max' => 255],
            [['fiscal_code'], 'string', 'max' => 16],
            [['name', 'surname'], 'string', 'max' => 50],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => $this->eventsModule->model('Event'), 'targetAttribute' => ['event_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\amos\core\user\User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['partner_of'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\amos\core\user\User::className(), 'targetAttribute' => ['partner_of' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosEvents::txt('ID'),
            'event_id' => AmosEvents::txt('Event ID'),
            'code' => AmosEvents::txt('UUID Code'),
            'email' => AmosEvents::txt('Email'),
            'fiscal_code' => AmosEvents::txt('#participant_codice_fiscale'),
            'name' => AmosEvents::txt('#participant_nome'),
            'surname' => AmosEvents::txt('#participant_cognome'),
            'notes' => AmosEvents::txt('#participant_note'),
            'company' => AmosEvents::txt('#participant_azienda'),
            'type' => AmosEvents::txt('Type of invited user - 1:registered, 2:imported'),
            'state' => AmosEvents::txt('State of invitation - 1:invited, 2:accepted, 3:rejected'),
            'invitation_sent_on' => AmosEvents::txt('Invitation Sent On'),
            'invitation_response_on' => AmosEvents::txt('Invitation Response On'),
            'user_id' => AmosEvents::txt('This is the id of the user in the system'),
            'partner_of' => AmosEvents::txt('This is the main user id who invited this one'),
            'created_at' => AmosEvents::txt('Created At'),
            'updated_at' => AmosEvents::txt('Updated At'),
            'deleted_at' => AmosEvents::txt('Deleted At'),
            'created_by' => AmosEvents::txt('Created By'),
            'updated_by' => AmosEvents::txt('Updated By'),
            'deleted_by' => AmosEvents::txt('Deleted By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne($this->eventsModule->model('Event'), ['id' => 'event_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\open20\amos\core\user\User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPartnerOf()
    {
        return $this->hasOne($this->eventsModule->model('EventInvitation'), ['id' => 'partner_of']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventInvitations()
    {
        return $this->hasMany($this->eventsModule->model('EventInvitation'), ['partner_of' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccreditationList()
    {
        return $this->hasOne($this->eventsModule->model('EventAccreditationList'), ['id' => 'accreditation_list_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanions()
    {
        return $this->hasMany($this->eventsModule->model('EventParticipantCompanion'), ['event_invitation_id' => 'id']);
    }
}
