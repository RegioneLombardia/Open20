<?php

namespace open20\amos\videoconference\models\base;

use open20\amos\admin\models\UserProfile;
use open20\amos\notificationmanager\models\Notification;
use Yii;
use open20\amos\videoconference\AmosVideoconference;

/**
 * This is the base-model class for table "videoconf".
 *
 * @property integer $id
 * @property string $id_room_videoconference
 * @property string $status
 * @property string $title
 * @property string $description
 * @property string $begin_date_hour
 * @property string $end_date_hour
 * @property integer $notification_before_conference
 * @property integer $reminder_sent
 * @property integer $community_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\videoconference\models\VideoconfUsersMm[] $videoconfUsersMms
 */
class Videoconf extends \open20\amos\core\record\Record
{
    /**
     *
     * @var type
     */
    public $end_date;
    public $end_hour;
    public $start_date;
    public $start_hour;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'videoconf';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['description'], 'string'],
            [['begin_date_hour', 'end_date_hour', 'notification_before_conference', 'end_hour', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by', 'reminder_sent', 'community_id'], 'integer'],
            [['id_room_videoconference', 'status', 'title'], 'string', 'max' => 255],
            [['start_date',/* 'end_date',*/ 'start_hour'], 'required'],
            [['notification_before_conference'], 'required'],
            [['begin_date_hour'], 'checkBeginDateHour'],
            [['start_date'], 'validateStartDate'],
            [['start_hour'], 'validateStartHour'],
            [
                'begin_date_hour', 'compare', 'compareAttribute' => 'end_date_hour', 'operator' => '<',
                'whenClient' => "function (attribute, value) {
                    var end_data = $('#end_date').val();
                    var end_hour = $('#end_hour').val();
                    var result = end_data + ' ' + end_hour; 
                    return result != '';
                }"
            ],
            [
                'end_date_hour', 'compare', 'compareAttribute' => 'begin_date_hour', 'operator' => '>',
                'whenClient' => "function (attribute, value) {
                    var start_data = $('#start_date').val();
                    var start_hour = $('#start_hour').val();
                    var result = start_data + ' ' + start_hour; 
                    return result != '';
                }"
            ],
        ];
    }

    /**
     * @param $attribute
     * @param $params
     * @return void
     */
    public function validateStartDate($attribute, $params)
    {
        $today = new \DateTime(); // Data odierna
        $start_date = \DateTime::createFromFormat('Y-m-d', $this->$attribute);

        if ($start_date !== false && $start_date < $today) {
            $this->addError($attribute, 'La data di inizio deve essere successiva alla data odierna.');
        }
    }

    /**
     * @param $attribute
     * @param $params
     * @return void
     */
    public function validateStartHour($attribute, $params)
    {
        $today = new \DateTime();
        $start_hour = new \DateTime($this->$attribute);//\DateTime::createFromFormat('HH:mm', $this->$attribute);
        $start_date = new \DateTime($this->start_date);
        if (
            $start_hour !== false
            && $start_hour < $today // l'orario è antecedente al momento attuale
            && (
                ($start_hour->format('i') != $today->format('i'))
                || ($start_hour->format('H') != $today->format('H'))
            ) // se i minuti coincidono va comunque bene, altrimenti di default vengono controllati pure i secondi
            && $start_date->format('Y-m-d') == $today->format('Y-m-d') // Se è un giorno futuro non c'è limite sull'orario
        )
        {
            $this->addError($attribute, 'L\' orario di inizio deve essere successivo all\' orario attuale.');
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function checkBeginDateHour()
    {
        $today = new \DateTime();
        if(!empty($this->begin_date_hour)) {
            $beginVideoConference = new \DateTime($this->begin_date_hour);
            if($beginVideoConference <= $today) {
                $this->addError('begin_date_hour', AmosVideoconference::t('amosvideoconference', '#checkbegin_date_hour'));
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosVideoconference::t('amosvideoconference', 'ID'),
            'id_room_videoconference' => AmosVideoconference::t('amosvideoconference', 'Id stanza Jitsi'),
            'status' => AmosVideoconference::t('amosvideoconference', 'Stato'),
            'title' => AmosVideoconference::t('amosvideoconference', 'Titolo della videoconferenza'),
            'description' => AmosVideoconference::t('amosvideoconference', 'Descrizione'),
            'begin_date_hour' => AmosVideoconference::t('amosvideoconference', 'Data e ora di inizio'),
            'end_date_hour' => AmosVideoconference::t('amosvideoconference', 'Data e ora di fine'),
            'notification_before_conference' => AmosVideoconference::t('amosvideoconference', 'Tempo notifica prima dell\'inizio (ore:minuti)'),
            'reminder_sent' => AmosVideoconference::t('amosvideoconference', 'Promemoria inviato'),
            'created_at' => AmosVideoconference::t('amosvideoconference', 'Creato il'),
            'updated_at' => AmosVideoconference::t('amosvideoconference', 'Aggiornato il'),
            'deleted_at' => AmosVideoconference::t('amosvideoconference', 'Cancellato il'),
            'created_by' => AmosVideoconference::t('amosvideoconference', 'Creato da'),
            'updated_by' => AmosVideoconference::t('amosvideoconference', 'Aggiornato da'),
            'deleted_by' => AmosVideoconference::t('amosvideoconference', 'Cancellato da'),
            'start_date' => AmosVideoconference::t('amosvideoconference', 'Data di inizio'),
            'start_hour' => AmosVideoconference::t('amosvideoconference', 'Ora di inizio'),
            'end_hour' => AmosVideoconference::t('amosvideoconference', 'Ora di fine'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVideoconfUsersMms()
    {
        return $this->hasMany(\open20\amos\videoconference\models\VideoconfUsersMm::className(), ['videoconf_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVideoconfUsersParticipants()
    {
        return $this->hasMany(\open20\amos\videoconference\models\VideoconfUsersMm::className(), ['videoconf_id' => 'id'])
            ->andWhere(['invited_only' => 0]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfileParticipants()
    {
        return $this->hasMany(UserProfile::className(), ['user_id' => 'user_profile_id'])->via('videoconfUsersParticipants');
    }
}