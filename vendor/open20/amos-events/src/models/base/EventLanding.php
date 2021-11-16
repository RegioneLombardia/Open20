<?php

namespace open20\amos\events\models\base;

use open20\amos\events\AmosEvents;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the base-model class for table "event_landing".
 *
 * @property integer $id
 * @property integer $event_id
 * @property integer $advanced_options_landing
 * @property string $description
 * @property string $schedule
 * @property integer $image_slider_id
 * @property integer $video_slider_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 * @property integer $ask_sex,
 * @property integer $ask_age,
 * @property integer $ask_county,
 * @property integer $ask_city,
 * @property integer $ask_telefon,
 * @property integer $ask_fiscal_code,
 * @property integer $ask_company,
 * @property integer $ask_tags,
 * @property integer $ask_sex_required,
 * @property integer $ask_age_required,
 * @property integer $ask_county_required,
 * @property integer $ask_city_required,
 * @property integer $ask_telefon_required,
 * @property integer $ask_fiscal_code_required,
 * @property integer $ask_company_required,
 * @property integer $ask_tags_required,
 * @property integer $social_reg,
 * @property integer $facebook_reg,
 * @property integer $twitter_reg,
 * @property integer $linkedin_reg,
 * @property integer $goolge_reg,
 * @property integer $apple_reg,
 * @property integer $spid_cns_reg,
 * @property integer $user_name_reg,
 * @property string $streaming_url
 * @property integer $enable_streaming
 * @property integer $streaming_type
 * @property string $date_begin_streaming
 * @property string $date_sending_memo
 * @property string $text_sending_memo
 * @property string $subject_sending_memo
 * @property integer $event_communication_memo_id
 *
 * @property \open20\amos\events\models\Event $event
 * @property \open20\amos\events\models\EventLandingNews[] $eventLandingNews
 * @property \open20\amos\events\models\EventLandingProtagonist[] $eventLandingProtagonists
 */
class  EventLanding extends \open20\amos\core\record\Record
{
    public $isSearch = false;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_landing';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [[ 'luya_template_id'], 'required'],
            [['event_communication_memo_id', 'streaming_type','enable_streaming','event_id', 'image_slider_id', 'video_slider_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['streaming_url','description', 'schedule','subject_sending_memo','text_sending_memo'], 'string'],
            [['date_begin_streaming','date_sending_memo','created_at', 'updated_at', 'deleted_at'], 'safe'],
            [
                [
                    'url',
                    'luya_page_id',
                    'luya_template_id',
                    'nav_id_tks_page',
                    'nav_id_wating_page',
                    'nav_id_already_present_page',
                    'userData',
                    'social_reg',
                    'facebook_reg',
                    'twitter_reg',
                    'linkedin_reg',
                    'goolge_reg',
                    'apple_reg',
                    'spid_cns_reg',
                    'user_name_reg',
                    'ask_sex',
                    'ask_age',
                    'ask_county',
                    'ask_city',
                    'ask_telefon',
                    'ask_fiscal_code',
                    'ask_company',
                    'ask_tags',

                    'ask_sex_required',
                    'ask_age_required',
                    'ask_county_required',
                    'ask_city_required',
                    'ask_telefon_required',
                    'ask_fiscal_code_required',
                    'ask_company_required',
                    'ask_tags_required',
                    'thank_you_subscribe',
                    'thank_you_registered',
                    'thank_you_waiting_list',
                    'advanced_options_landing'
                ]
                , 'safe'
            ],
            ['url', 'checkAvailabilityUrl'],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\amos\events\models\Event::className(), 'targetAttribute' => ['event_id' => 'id']],
        ];

        if($this->event->eventType->event_type != \open20\amos\events\models\EventType::TYPE_INFORMATIVE){
            $rules = ArrayHelper::merge($rules, [
                ['date_sending_memo', 'compare', 'compareAttribute' => 'date_begin_streaming', 'operator' => '<='],
                ['date_begin_streaming', 'compare', 'compareAttribute' => 'date_sending_memo', 'operator' => '>='],
            ]);
        }
        return $rules;
    }

    public function checkAvailabilityUrl(){
        $utility = new \amos\cmsbridge\utility\CmsUtility();
        $ok = $utility->isUrlOk($this->url, $this->luya_page_id) ;
        if(!$ok){
            $this->addError('url', "L'url della landing esiste già");
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosevents', 'ID'),
            'event_id' => Yii::t('amosevents', 'Event'),
            'luya_template_id' => AmosEvents::t('amosevents', 'Template'),
            'description' => Yii::t('amosevents', 'Presentation'),
            'schedule' => Yii::t('amosevents', 'Schedule'),
            'streaming_type' => Yii::t('amosevents', 'Tipologia streaming'),
            'streaming_url' => Yii::t('amosevents', 'Url streaming'),
            'text_sending_memo' => Yii::t('amosevents', 'Testo promemoria'),
            'subject_sending_memo' => Yii::t('amosevents', 'Oggetto promemoria'),
            'date_sending_memo' => Yii::t('amosevents', 'Data invio promemoria'),
            'date_begin_streaming' => Yii::t('amosevents', 'Data avvio stereaming'),
            'image_slider_id' => Yii::t('amosevents', 'Slider images'),
            'video_slider_id' => Yii::t('amosevents', 'Slider videos'),
            'created_at' => Yii::t('amosevents', 'Created at'),
            'updated_at' => Yii::t('amosevents', 'Updated at'),
            'deleted_at' => Yii::t('amosevents', 'Deleted at'),
            'created_by' => Yii::t('amosevents', 'Created by'),
            'updated_by' => Yii::t('amosevents', 'Updated at'),
            'deleted_by' => Yii::t('amosevents', 'Deleted at'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(\open20\amos\events\models\Event::className(), ['id' => 'event_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventLandingNews()
    {
        return $this->hasMany(\open20\amos\events\models\EventLandingNews::className(), ['event_landing_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventLandingProtagonists()
    {
        return $this->hasMany(\open20\amos\events\models\EventLandingProtagonist::className(), ['event_landing_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImageSlider()
    {
        return $this->hasOne(\amos\sitemanagement\models\SiteManagementSlider::className(), ['id' => 'image_slider_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVideoSlider()
    {
        return $this->hasOne(\amos\sitemanagement\models\SiteManagementSlider::className(), ['id' => 'video_slider_id']);
    }


}
