<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\agid\service\models\base
 * @category   CategoryName
 */

namespace open20\agid\service\models\base;

use Yii;

/**
 * This is the base-model class for table "agid_content_type_service".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $content_type_icon
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\agid\service\models\AgidService[] $agidServices
 */
abstract class AgidServiceContentType extends \open20\amos\core\record\Record
{
    public $isSearch = false;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agid_service_content_type';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['name', 'content_type_icon'], 'string', 'max' => 255],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('agid-service', 'ID'),
            'name' => Yii::t('agid-service', 'Nome'),
            'description' => Yii::t('agid-service', 'Descrizione'),
            'content_type_icon' => Yii::t('agid-person', 'Icon'),
            'created_at' => Yii::t('agid-service', 'Created at'),
            'updated_at' => Yii::t('agid-service', 'Updated at'),
            'deleted_at' => Yii::t('agid-service', 'Deleted at'),
            'created_by' => Yii::t('agid-service', 'Created by'),
            'updated_by' => Yii::t('agid-service', 'Updated by'),
            'deleted_by' => Yii::t('agid-service', 'Deleted by'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgidServices()
    {
        return $this->hasMany(\open20\agid\service\models\AgidService::className(), ['agid_content_type_service_id' => 'id']);
    }
}
