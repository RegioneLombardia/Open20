<?php

namespace open20\amos\community\models\base;

use Yii;

/**
 * This is the base-model class for table "community_user_field_type".
 *
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\community\models\CommunityUserField[] $communityUserFields
 */
class  CommunityUserFieldType extends \open20\amos\core\record\Record
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'community_user_field_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
            [['description', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amoscommunity', 'ID'),
            'description' => Yii::t('amoscommunity', 'Description'),
            'type' => Yii::t('amoscommunity', 'Type'),
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
    public function getCommunityUserFields()
    {
        return $this->hasMany(\open20\amos\community\models\CommunityUserField::className(), ['user_field_type_id' => 'id']);
    }
}
