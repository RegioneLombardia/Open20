<?php

namespace backend\modules\campains\models\base;

use backend\modules\campains\Module;
use open20\amos\notificationmanager\behaviors\NotifyBehavior;
use open20\amos\notificationmanager\record\NotifyRecord;
use preference\userprofile\models\PreferenceChannel;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * This is the base-model class for table "preference_campain_channel_mm".
 *
 * @property integer $id
 * @property integer $preference_campain_id
 * @property integer $preference_channel_id
 * @property string $start_date
 * @property string $end_date
 * @property string $title
 * @property string $subtitle
 * @property string $subject
 * @property string $status
 * @property string $mailup_error_message
 * @property integer $mailup_errors
 * @property string $mailup_stats_number_comunication_sent
 * @property string $mailup_stats_number_comunication_bounces
 * @property string $mailup_stats_number_comunication_clicks
 * @property string $mailup_stats_number_comunication_views
 * @property integer $template_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 * @property integer $date_newsletter
 *
 * @property \backend\modules\campains\models\PreferenceCampain $preferenceCampain
 * @property preference\userprofile\models\PreferenceChannel $preferenceChannel
 * @property \backend\modules\campains\models\PreferenceCampainSection[] $preferenceCampainSections
 */
class PreferenceCampainChannelMm extends NotifyRecord {

    const STATUS_USERS_TO_IMPORT = 'user_to_import';
    const STATUS_IMPORTING_USERS = 'importing_users';
    const STATUS_IMPORTED = 'imported';
    const STATUS_TO_SEND = 'to_send';
    const STATUS_SENDING = 'sending';
    const STATUS_SENT = 'sent';
    const STATUS_ERROR = 'error';
    const STATUS_NO_USER_TO_IMPORT = 'no_user_to_import';

    public $isSearch = false;
    public $template;
    public $phone_number;

    const SCENARIO_NEWSLETTER = 'newsletter';
    const SCENARIO_APP = 'app';
    const SCENARIO_SMS = 'sms';
    const SCENARIO_DEFAULT = 'default';

    public function scenarios()
    {
        $forAll = ['mailup_sending_id', 'mailup_message_id', 'mailup_import_id', 'mailup_group_id'
            , 'mailup_list_id', 'mailup_n_sent', 'created_by', 'updated_by', 'deleted_by', 'start_date', 'end_date'
            , 'status', 'mailup_status', 'date_newsletter', 'preference_campain_id', 'preference_channel_id', 'mailup_error_message'
            , 'mailup_stats_number_comunication_sent', 'mailup_stats_number_comunication_bounces'
            , 'mailup_stats_number_comunication_clicks', 'mailup_stats_number_comunication_views', 'mailup_errors'];


        return [
            self::SCENARIO_NEWSLETTER => ArrayHelper::merge($forAll,[
                'title', 'subtitle', 'subject', 'status', 'template_id'
            ]),
            self::SCENARIO_APP => ArrayHelper::merge($forAll,[
                'title', 'date_app', 'status'
            ]),
            self::SCENARIO_SMS => ArrayHelper::merge($forAll,[
                'sms_text',
            ]),
            self::SCENARIO_DEFAULT => $forAll,
        ];
    }

    /**
     * Behaviors
    *
    * @return void
    */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
            [
            'NotifyBehavior' => [
                'class' => NotifyBehavior::className(),
                'conditions' => [],
            ],
        ]);
    }

        /**
     * Init 
     *
     * @return void
     */
    public function init()
    {
        parent::init();

    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'preference_campain_channel_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['mailup_sending_id', 'mailup_message_id', 'mailup_import_id', 'mailup_group_id', 'mailup_list_id'
                , 'mailup_n_sent', 'mailup_stats_number_comunication_sent', 'mailup_stats_number_comunication_bounces'],'integer'],
            [['preference_campain_id', 'preference_channel_id', 'template_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['start_date', 'end_date', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['title', 'subtitle', 'subject', 'status', 'sms_text', 'date_newsletter', 'mailup_status'], 'string', 'max' => 255],
            [['preference_campain_id'], 'exist', 'skipOnError' => true, 'targetClass' => PreferenceCampain::className(), 'targetAttribute' => ['preference_campain_id' => 'id']],
            [['preference_channel_id'], 'exist', 'skipOnError' => true, 'targetClass' => PreferenceChannel::className(), 'targetAttribute' => ['preference_channel_id' => 'id']],

            [['template_id', 'title', 'subject'], 'required', 'on' => self::SCENARIO_NEWSLETTER],
            [['title', 'date_app'], 'required', 'on' => self::SCENARIO_APP],
            [['sms_text'], 'required', 'on' => self::SCENARIO_SMS],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Module::t('campains', 'ID'),
            'preference_campain_id' => Module::t('campains', 'Preference campain'),
            'preference_channel_id' => Module::t('campains', 'Preference channel'),
            'start_date' => Module::t('campains', 'Start date'),
            'end_date' => Module::t('campains', ' End date'),
            'title' => Module::t('campains', 'Titolo'),
            'date_app' => Module::t('campains', 'Data ordinamento'),
            'subtitle' => Module::t('campains', 'Sottotitolo'),
            'subject' => Module::t('campains', 'Oggetto'),
            'status' => Module::t('campains', 'Staus'),
            'template_id' => Module::t('campains', 'Template'),
            'sms_text' => Module::t('campains', 'Sms Text'),
            'created_at' => Module::t('campains', 'Created at'),
            'updated_at' => Module::t('campains', 'Updated at'),
            'deleted_at' => Module::t('campains', 'Deleted at'),
            'created_by' => Module::t('campains', 'Created by'),
            'updated_by' => Module::t('campains', 'Updated by'),
            'deleted_by' => Module::t('campains', 'Deleted by'),
            'date_newsletter' => Module::t('campains', 'Data'),
        ];
    }

        /**
     * @return array
     */
    public static function getLabelStatus()
    {
        return [
            self::STATUS_USERS_TO_IMPORT => Module::t('campains',"Da processare"),
            self::STATUS_IMPORTING_USERS =>  Module::t('campains','In preparazione'),
            self::STATUS_IMPORTED => Module::t('campains','In Coda'),
            self::STATUS_TO_SEND => Module::t('campains','Da inviare'),
            self::STATUS_SENDING => Module::t('campains','In invio'),
            self::STATUS_SENT => Module::t('campains','Invio completato'),
            self::STATUS_ERROR => Module::t('campains','Errore, impossibile portare a termine il processo'),
            self::STATUS_NO_USER_TO_IMPORT => Module::t('campains','Nessun utente trovato')

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreferenceCampain() {
        return $this->hasOne(\backend\modules\campains\models\PreferenceCampain::className(), ['id' => 'preference_campain_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreferenceChannel() {
        return $this->hasOne(\preference\userprofile\models\PreferenceChannel::className(), ['id' => 'preference_channel_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreferenceCampainSections() {
        return $this->hasMany(\backend\modules\campains\models\PreferenceCampainSection::className(), ['preference_campain_channel_mm_id' => 'id']);
    }

}
