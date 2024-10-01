<?php

namespace open20\amos\utility\models\base;

use open20\amos\core\record\Record;
use Yii;

/**
 * This is the base-model class for table "platform_configs".
 *
 * @property integer $id
 * @property string $module
 * @property string $key
 * @property string $value
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property integer $deleted_by
 * @property string $deleted_at
 */
class  PlatformConfigs extends Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'platform_configs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value'], 'string'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['module', 'key'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosutility', 'ID'),
            'module' => Yii::t('amosutility', 'Module'),
            'key' => Yii::t('amosutility', 'Key'),
            'value' => Yii::t('amosutility', 'Value'),
            'created_by' => Yii::t('amosutility', 'Created By'),
            'created_at' => Yii::t('amosutility', 'Created At'),
            'updated_by' => Yii::t('amosutility', 'Updated By'),
            'updated_at' => Yii::t('amosutility', 'Updated At'),
            'deleted_by' => Yii::t('amosutility', 'Deleted By'),
            'deleted_at' => Yii::t('amosutility', 'Deleted At'),
        ];
    }

    /**
     * @param $module
     * @param $key
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getConfigValue($module, $key){
        return \open20\amos\utility\models\PlatformConfigs::find()
            ->andWhere(['module' => $module])
            ->andWhere(['key' => $key])
            ->one();
    }
}
