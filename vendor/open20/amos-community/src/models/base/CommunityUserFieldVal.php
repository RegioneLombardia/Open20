<?php

namespace open20\amos\community\models\base;

use Yii;

/**
 * This is the base-model class for table "community_user_field_val".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $user_field_id
 * @property string $value
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\community\models\CommunityUserField $userField
 * @property \open20\amos\core\user\User $user
 */
class  CommunityUserFieldVal extends \open20\amos\core\record\Record
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'community_user_field_val';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'user_field_id', 'value'], 'required'],
            [['user_id', 'user_field_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['value'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['user_field_id'], 'exist', 'skipOnError' => true, 'targetClass' => CommunityUserField::className(), 'targetAttribute' => ['user_field_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\amos\core\user\User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amoscommunity', 'ID'),
            'user_id' => Yii::t('amoscommunity', 'User'),
            'user_field_id' => Yii::t('amoscommunity', 'Field'),
            'value' => Yii::t('amoscommunity', 'Value'),
            'created_at' => Yii::t('amoscommunity', 'Created at'),
            'updated_at' => Yii::t('amoscommunity', 'Updated at'),
            'deleted_at' => Yii::t('amoscommunity', 'Deleted at'),
            'created_by' => Yii::t('amoscommunity', 'Created by'),
            'updated_by' => Yii::t('amoscommunity', 'Updated at'),
            'deleted_by' => Yii::t('amoscommunity', 'Deleted at'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserField()
    {
        return $this->hasOne(\open20\amos\community\models\CommunityUserField::className(), ['id' => 'user_field_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\open20\amos\core\user\User::className(), ['id' => 'user_id']);
    }
}
