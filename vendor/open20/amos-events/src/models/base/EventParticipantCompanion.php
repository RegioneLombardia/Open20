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

use open20\amos\core\record\Record;
use open20\amos\core\validators\CFValidator;
use open20\amos\events\AmosEvents;
use yii\helpers\ArrayHelper;

/**
 * Class EventParticipantCompanion
 *
 * This is the base-model class for table "event_participant_companion".
 *
 * @property integer $id
 * @property string $nome
 * @property string $cognome
 * @property string $email
 * @property string $codice_fiscale
 * @property string $azienda
 * @property string $note
 * @property integer $presenza
 * @property string $presenza_scansionata_il
 * @property integer $event_invitation_id
 * @property integer $event_accreditation_list_id
 * @property integer $user_id
 *
 * @property \open20\amos\core\user\User $user
 * @property \open20\amos\events\models\EventAccreditationList $accreditationList
 * @property \open20\amos\events\models\EventSeats $assignedSeat
 *
 * @package open20\amos\events\models
 */
abstract class EventParticipantCompanion extends Record
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
        return 'event_participant_companion';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->eventsModule = AmosEvents::instance();
        parent::init();
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\open20\amos\core\user\User::className(), ['id' => 'user_id']);
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
