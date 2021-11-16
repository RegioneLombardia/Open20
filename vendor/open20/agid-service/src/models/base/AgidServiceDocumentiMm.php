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
 * This is the base-model class for table "agid_service_documenti_mm".
 *
 * @property integer $id
 * @property integer $documenti_id
 * @property integer $agid_service_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\agid\service\models\AgidService $agidService
 * @property \open20\amos\documenti\models\Documenti $documenti
 */
abstract class AgidServiceDocumentiMm extends \open20\amos\core\record\Record
{
    public $isSearch = false;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agid_service_documenti_mm';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['documenti_id', 'agid_service_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['agid_service_id'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\agid\service\models\AgidService::className(), 'targetAttribute' => ['agid_service_id' => 'id']],
            [['documenti_id'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\amos\documenti\models\Documenti::className(), 'targetAttribute' => ['documenti_id' => 'id']],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('agid-service', 'ID'),
            'documenti_id' => Yii::t('agid-service', 'Documenti'),
            'agid_service_id' => Yii::t('agid-service', 'Service'),
            'created_at' => Yii::t('agid-service', 'Created at'),
            'updated_at' => Yii::t('agid-service', 'Updated at'),
            'deleted_at' => Yii::t('agid-service', 'Deleted at'),
            'created_by' => Yii::t('agid-service', 'Created at'),
            'updated_by' => Yii::t('agid-service', 'Updated by'),
            'deleted_by' => Yii::t('agid-service', 'Deleted by'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgidService()
    {
        return $this->hasOne(\open20\agid\service\models\AgidService::className(), ['id' => 'agid_service_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumenti()
    {
        return $this->hasOne(\open20\amos\documenti\models\Documenti::className(), ['id' => 'documenti_id']);
    }
}
