<?php

namespace open20\socialwall\models\base;

use Yii;

/**
 * This is the base-model class for table "socialwall_live_contents".
 *
 * @property integer $id
 * @property integer $socialwall_id
 * @property string $json_data_content
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\socialwall\models\Socialwall $socialwall
 */
abstract class  SocialwallLiveContents extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'socialwall_live_contents';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['socialwall_id'], 'required'],
            [['socialwall_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['json_data_content'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['socialwall_id'], 'exist', 'skipOnError' => true, 'targetClass' => Socialwall::className(), 'targetAttribute' => ['socialwall_id' => 'id']],
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
            'json_data_content' => Yii::t('socialwall', 'Json Data Fetched'),
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
}
