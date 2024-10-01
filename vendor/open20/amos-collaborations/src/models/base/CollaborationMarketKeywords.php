<?php

namespace open20\amos\collaborations\models\base;

use Yii;

/**
 * This is the base-model class for table "collaboration_market_keywords".
 *
 * @property integer $id
 * @property integer $code
 * @property string $description
 * @proporty string $category
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $deleted_by
 * @property string $deleted_at
 */
class  CollaborationMarketKeywords extends \yii\db\ActiveRecord
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'collaboration_market_keywords';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'description', 'category'], 'required'],
            [['code', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['description', 'category'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amoscollaborations', 'ID'),
            'code' => Yii::t('amoscollaborations', 'Code'),
            'description' => Yii::t('amoscollaborations', 'Description'),
            'category' => Yii::t('amoscollaborations', 'Category'),
            'created_by' => Yii::t('amoscollaborations', 'Created By'),
            'created_at' => Yii::t('amoscollaborations', 'Created At'),
            'updated_by' => Yii::t('amoscollaborations', 'Updated By'),
            'updated_at' => Yii::t('amoscollaborations', 'Updated At'),
            'deleted_by' => Yii::t('amoscollaborations', 'Deleted By'),
            'deleted_at' => Yii::t('amoscollaborations', 'Deleted At'),
        ];
    }
}
