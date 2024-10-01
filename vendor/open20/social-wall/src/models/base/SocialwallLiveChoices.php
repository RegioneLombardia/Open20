<?php

namespace open20\socialwall\models\base;

use Yii;

/**
 * This is the base-model class for table "socialwall_live_choices".
 *
 * @property integer $id
 * @property integer $socialwall_id
 * @property integer $socialwall_tokens_id
 * @property string $keywords
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\socialwall\models\Socialwall $socialwall
 * @property \open20\socialwall\models\SocialwallTokens $socialwallTokens
 */
abstract class  SocialwallLiveChoices extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'socialwall_live_choices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['socialwall_id', 'socialwall_tokens_id', 'keywords'], 'required'],
            [['socialwall_id', 'socialwall_tokens_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['keywords'], 'string', 'max' => 500],
            [['socialwall_id'], 'exist', 'skipOnError' => true, 'targetClass' => Socialwall::className(), 'targetAttribute' => ['socialwall_id' => 'id']],
            [['socialwall_tokens_id'], 'exist', 'skipOnError' => true, 'targetClass' => SocialwallTokens::className(), 'targetAttribute' => ['socialwall_tokens_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('socialwall', 'ID'),
            'socialwall_id' => Yii::t('socialwall', 'Related Social Wall'),
            'socialwall_tokens_id' => Yii::t('socialwall', 'Related Social Wall Tokens'),
            'keywords' => Yii::t('socialwall', 'Keywords'),
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
    public function getSocialwall()
    {
        return $this->hasOne(\open20\socialwall\models\Socialwall::className(), ['id' => 'socialwall_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSocialwallTokens()
    {
        return $this->hasOne(\open20\socialwall\models\SocialwallTokens::className(), ['id' => 'socialwall_tokens_id']);
    }
}
