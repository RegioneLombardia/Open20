<?php

namespace open20\amos\events\models;

use open20\amos\core\validators\CFValidator;
use open20\amos\events\AmosEvents;
use yii\helpers\ArrayHelper;

/**
 * This is the base-model class for table "user_profile".
 *
 * @property integer $event_id
 * @property string $nome
 * @property string $cognome
 * @property string $codice_fiscale
 * @property string $email
 * @property string $azienda
 * @property string $note
 * @property integer $presenza
 * @property string $presenza_scansionata_il
 * @property integer $event_invitation_id
 * @property integer $event_accreditation_list_id
 * @property integer $user_id
 */
class EventParticipantCompanion extends \open20\amos\core\record\Record
{
    public $event_id;

    /**
     * @var AmosEvents $eventsModule
     */
    public $eventsModule = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%event_participant_companion}}';
    }

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
        $rules = [
            [[
                'azienda',
                'note',
            ], 'string'],
            [[
                'nome',
                'cognome',
                'azienda',
                'note',
            ], 'safe'],
            [[
                'presenza',
                'user_id'
            ], 'integer'],
            [[
                'event_invitation_id',
                'event_accreditation_id',
                'presenza_scansionata_il',
                'user_id',
            ], 'safe'],
            ['email', 'email'],
            ['codice_fiscale', CFValidator::className()],
            [[
                'nome',
                'cognome',
                'email'
            ], 'required'],
        ];

        $eventTmp = $this->getEvent();
        if (!empty($eventTmp) && $eventTmp->abilita_codice_fiscale_in_form) {
            $rules[] = [
                ['codice_fiscale'], 'required'
            ];
        }

        return ArrayHelper::merge(
            parent::rules(), $rules
        );
    }

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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'nome' => AmosEvents::t('amosevents', 'Nome'),
            'cognome' => AmosEvents::t('amosevents', 'Cognome'),
            'email' => AmosEvents::t('amosevents', 'Email'),
            'codice_fiscale' => AmosEvents::t('amosevents', 'Codice Fiscale'),
            'azienda' => AmosEvents::t('amosevents', 'Azienda'),
            'note' => AmosEvents::t('amosevents', 'Note'),
        ]);
    }

    public function getAccreditationList()
    {
        return $this->hasOne($this->eventsModule->model('EventAccreditationList'), ['id' => 'event_accreditation_list_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssignedSeat()
    {
        return $this->hasOne($this->eventsModule->model('EventSeats'), ['event_participant_companion_id' => 'id']);
    }
}
