<?php

namespace open20\amos\community\models\base;

use Yii;

/**
 * This is the base-model class for table "community_user_field_default_val".
 *
 * @property integer $id
 * @property string $community_user_field_id
 * @property string $value
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class  CommunityUserFieldDefaultVal extends \open20\amos\core\record\Record
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'community_user_field_default_val';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['community_user_field_id'], 'required'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['community_user_field_id', 'value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amoscommunity', 'ID'),
            'community_user_field_id' => Yii::t('amoscommunity', 'Field'),
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
    public function getCommunityUserField()
    {
        return $this->hasOne(\open20\amos\community\models\CommunityUserField::className(), [ 'id' => 'community_user_field_id']);
    }
}
