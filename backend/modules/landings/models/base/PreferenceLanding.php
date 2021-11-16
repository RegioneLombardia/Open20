<?php

namespace backend\modules\landings\models\base;

use backend\modules\operators\models\PreferenceStructure;
use open20\amos\news\models\News;
use open20\amos\documenti\models\Documenti;
use open20\amos\sondaggi\models\Sondaggi;
use Yii;
use yii\helpers\StringHelper;

/**
 * This is the base-model class for table "preference_landing".
 *
 * @property integer $id
 * @property integer $preference_structure_id
 * @property string $status
 * @property string $title
 * @property string $description
 * @property string $schedule
 * @property integer $image_slider_id
 * @property integer $video_slider_id
 * @property integer $luya_page_id
 * @property integer $luya_template_id
 * @property integer $sondaggi_id
 * @property boolean $rating_flag
 * @property boolean $rating_description
 * @property boolean $playlist_youtube_description
 * @property boolean $playlist_youtube_url
 * @property string $url
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \backend\modules\landings\models\PreferenceStructure $preferenceStructure
 */
class  PreferenceLanding extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'preference_landing';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'url','preference_structure_id'], 'required'],
            [['preference_structure_id', 'image_slider_id', 'video_slider_id', 'luya_page_id', 'luya_template_id'
                , 'created_by', 'updated_by', 'deleted_by', 'sondaggi_id'], 'integer'],
            [['description', 'schedule'], 'string'],
            [['rating_description'], 'string'],
            [['rating_flag'], 'boolean'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['playlist_youtube_url'], 'url'],
            [['status', 'title', 'playlist_youtube_description', 'playlist_youtube_url'], 'string', 'max' => 255],
            [['url'], 'string', 'max' => 80],
            [['url'], 'myUrlValidate'],
            [['preference_structure_id'], 'exist', 'skipOnError' => true, 'targetClass' => PreferenceStructure::className(), 'targetAttribute' => ['preference_structure_id' => 'id']],
            [['sondaggi_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sondaggi::className(), 'targetAttribute' => ['sondaggi_id' => 'id']],
        ];
    }

    public function myUrlValidate($attribute, $params, $validator)
    {
        $specialChars = ["-" , "_" , "|" , "+"];

        foreach ($specialChars as $char) {
            if(StringHelper::endsWith($this->$attribute, $char)){
                $this->addError($attribute, 'Non può terminare con "'.$char.'"');
            }
        }

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('preferencelandings', 'ID'),
            'preference_structure_id' => Yii::t('preferencelandings', 'Structure'),
            'status' => Yii::t('preferencelandings', 'status'),
            'title' => Yii::t('preferencelandings', 'Title'),
            'description' => Yii::t('preferencelandings', 'Descrizione testo'),
            'schedule' => Yii::t('preferencelandings', 'Descrizione apertura'),
            'image_slider_id' => Yii::t('preferencelandings', 'Slider images'),
            'video_slider_id' => Yii::t('preferencelandings', 'Slider videos'),
            'luya_page_id' => Yii::t('preferencelandings', 'Luya page'),
            'luya_template_id' => Yii::t('preferencelandings', 'Luya template'),
            'url' => Yii::t('preferencelandings', 'Url'),
            'playlist_youtube_url' => Yii::t('preferencelandings', 'URL'),
            'playlist_youtube_description' => Yii::t('preferencelandings', 'Descrizione'),
            'rating_flag' => Yii::t('preferencelandings', 'Abilita nella landing page'),
            'rating_description' => Yii::t('preferencelandings', 'Descrizione'),
            'created_at' => Yii::t('preferencelandings', 'Created at'),
            'updated_at' => Yii::t('preferencelandings', 'Updated at'),
            'deleted_at' => Yii::t('preferencelandings', 'Deleted at'),
            'created_by' => Yii::t('preferencelandings', 'Created by'),
            'updated_by' => Yii::t('preferencelandings', 'Updated by'),
            'deleted_by' => Yii::t('preferencelandings', 'Deleted by'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreferenceStructure()
    {
        return $this->hasOne(PreferenceStructure::className(), ['id' => 'preference_structure_id']);
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
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreferenceLandingProtagonists()
    {
        return $this->hasMany(\backend\modules\landings\models\PreferenceLandingProtagonist::className(), ['preference_landing_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreferenceLandingNews()
    {
        return $this->hasMany(\backend\modules\landings\models\PreferenceLandingNews::className(), ['preference_landing_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreferenceLandingDocumenti()
    {
        return $this->hasMany(\backend\modules\landings\models\PreferenceLandingDocumenti::className(), ['preference_landing_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasMany(News::className(), ['id' => 'news_id'])->via('preferenceLandingNews');
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLinks()
    {
        return $this->hasMany(PreferenceLandingLink::className(), ['preference_landing_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumenti()
    {
        return $this->hasMany(Documenti::className(), ['id' => 'documenti_id'])->via('preferenceLandingDocumenti');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSondaggio()
    {
        return $this->hasOne(Sondaggi::className(), ['id' => 'sondaggi_id']);
    }

}
