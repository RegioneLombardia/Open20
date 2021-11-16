<?php

namespace preference\userprofile\models;

use open20\amos\core\record\Record;
use Yii;

/**
 * This is the base-model class for table "preference_topic_prl_to_pc".
 *
 * @property integer $id
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by

 */
class PreferenceTopicPrlToPc extends Record
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'preference_topic_prl_to_pc';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['prl'], 'string', 'max' => 255],
            [['pc'], 'string', 'max' => 255],
        ];
    }

/**
 * @inheritdoc
 */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('preferenceuser', 'ID'),
            'prl' => Yii::t('preferenceuser', ''),
            'pc' => Yii::t('preferenceuser', ''),
            'created_at' => Yii::t('preferenceuser', 'Created at'),
            'updated_at' => Yii::t('preferenceuser', 'Updated at'),
            'deleted_at' => Yii::t('preferenceuser', 'Deleted at'),
            'created_by' => Yii::t('preferenceuser', 'Created by'),
            'updated_by' => Yii::t('preferenceuser', 'Updated by'),
            'deleted_by' => Yii::t('preferenceuser', 'Deleted by'),
        ];
    }

}
