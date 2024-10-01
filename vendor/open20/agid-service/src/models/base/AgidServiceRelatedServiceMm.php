<?php

namespace open20\agid\service\models\base;


use Yii;

/**
 * This is the base-model class for table "agid_service_related_service_mm".
 *
 * @property integer $id
 * @property integer $agid_service_id
 * @property integer $agid_related_service_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\agid\service\models\AgidService $agidRelatedService
 * @property \open20\agid\service\models\AgidService $agidService
 */
class AgidServiceRelatedServiceMm extends \open20\amos\core\record\Record
{
    public $isSearch = false;

	/**
	 * @inheritdoc
	 */
    public static function tableName()
    {
        return 'agid_service_related_service_mm';
    }

	/**
	 * @inheritdoc
	 */
    public function rules()
    {
        return [
            [['agid_service_id', 'agid_related_service_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['agid_related_service_id'], 'exist', 'skipOnError' => true, 'targetClass' => AgidService::className(), 'targetAttribute' => ['agid_related_service_id' => 'id']],
            [['agid_service_id'], 'exist', 'skipOnError' => true, 'targetClass' => AgidService::className(), 'targetAttribute' => ['agid_service_id' => 'id']],
        ];
    }

	/**
	 * @inheritdoc
	 */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'agid_service_id' => Yii::t('app', 'Agid Service'),
            'agid_related_service_id' => Yii::t('app', 'Agid Related Service'),
            'created_at' => Yii::t('app', 'Created at'),
            'updated_at' => Yii::t('app', 'Updated at'),
            'deleted_at' => Yii::t('app', 'Deleted at'),
            'created_by' => Yii::t('app', 'Created at'),
            'updated_by' => Yii::t('app', 'Updated by'),
            'deleted_by' => Yii::t('app', 'Deleted by'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgidRelatedService()
    {
        return $this->hasOne(\open20\agid\service\models\AgidService::className(), ['id' => 'agid_related_service_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgidService()
    {
        return $this->hasOne(\open20\agid\service\models\AgidService::className(), ['id' => 'agid_service_id']);
    }
}
