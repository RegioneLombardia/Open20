<?php

namespace open20\socialwall\models\base;

use Yii;

/**
 * This is the base-model class for table "socialwall".
 *
 * @property integer $id
 * @property string $module_id
 * @property integer $module_record_id
 * @property string $type
 * @property bool $update
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\socialwall\models\SocialwallLiveChoices[] $socialwallLiveChoices
 * @property \open20\socialwall\models\SocialwallLiveContents[] $socialwallLiveContents
 */
abstract class  Socialwall extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    public $socialwallType;
    public $socialwallLiveChoices;
    public $socialwallLiveTags;
    public $socialChoices;
    public $socialwallPickedPosts;
    public $socialTags;
    public $since;
    public $until;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'socialwall';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['module_id', 'module_record_id', 'type'], 'required'],
            [['module_record_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['update'], 'boolean'],
            [['created_at', 'updated_at', 'deleted_at', 'since', 'until', 'socialwallType', 'socialwallLiveChoices', 'socialwallLiveTags', 'socialwallPickedPosts'], 'safe'],
            [['module_id'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('socialwall', 'ID'),
            'module_id' => Yii::t('socialwall', 'Related Module'),
            'module_record_id' => Yii::t('socialwall', 'Related Module Id'),
            'type' => Yii::t('socialwall', 'Type'),
            'socialwallLiveChoices' => Yii::t('socialwall', 'Social configurati'),
            'socialChoices' => Yii::t('socialwall', 'Social configurati'),
            'socialwallLiveTags' => Yii::t('socialwall', 'Tag'),
            'socialwallPickedPosts' => Yii::t('socialwall', 'Asset social selezionati'),
            'since' => Yii::t('socialwall', 'Data pubblicazione inizio'),
            'until' => Yii::t('socialwall', 'Data pubblicazione fine'),
            'update' => Yii::t('socialwall', 'Permetti aggiornamento automatico contenuti'),
            'socialTags' => Yii::t('socialwall', 'Tag'),
            'created_at' => Yii::t('socialwall', 'Created at'),
            'updated_at' => Yii::t('socialwall', 'Updated at'),
            'deleted_at' => Yii::t('socialwall', 'Deleted at'),
            'created_by' => Yii::t('socialwall', 'Created by'),
            'updated_by' => Yii::t('socialwall', 'Updated at'),
            'deleted_by' => Yii::t('socialwall', 'Deleted at'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSocialwallLiveChoices()
    {
        return $this->hasMany(\open20\socialwall\models\SocialwallLiveChoices::className(), ['socialwall_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSocialwallLiveContents()
    {
        return $this->hasMany(\open20\socialwall\models\SocialwallLiveContents::className(), ['socialwall_id' => 'id']);
    }
}
