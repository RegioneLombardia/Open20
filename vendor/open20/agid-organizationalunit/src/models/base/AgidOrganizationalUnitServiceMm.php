<?php

namespace open20\agid\organizationalunit\models\base;

use Yii;

/**
 * This is the base-model class for table "agid_organizational_unit_service_mm".
 *
 * @property integer $id
 * @property integer $agid_organizational_unit_id
 * @property integer $agid_service_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class AgidOrganizationalUnitServiceMm extends \open20\amos\core\record\ContentModel
{
    public $isSearch = false;

	/**
	 * @inheritdoc
	 */
    public static function tableName()
    {
        return 'agid_organizational_unit_service_mm';
    }

	/**
	 * @inheritdoc
	 */
    public function rules()
    {
        return [
            [['agid_organizational_unit_id', 'agid_service_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

	/**
	 * @inheritdoc
	 */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosorganizationalunit', 'ID'),
            'agid_organizational_unit_id' => Yii::t('amosorganizationalunit', 'Agid Organizational Unit'),
            'agid_service_id' => Yii::t('amosorganizationalunit', 'Agid Service'),
            'created_at' => Yii::t('amosorganizationalunit', 'Created at'),
            'updated_at' => Yii::t('amosorganizationalunit', 'Updated at'),
            'deleted_at' => Yii::t('amosorganizationalunit', 'Deleted at'),
            'created_by' => Yii::t('amosorganizationalunit', 'Created at'),
            'updated_by' => Yii::t('amosorganizationalunit', 'Updated by'),
            'deleted_by' => Yii::t('amosorganizationalunit', 'Deleted by'),
        ];
    }

    /**
     * 
     * @param bool $truncate
     * @return string
     */
    public function getDescription($truncate) 
    {
        $ret = $this->name;
        if ($truncate) {
            $ret = $this->__shortText($this->name, 200);
        }
        return $ret;
    }

    /**
     * 
     * @return array
     */
    public function getGridViewColumns() 
    {
        return [];
    }

    /**
     * 
     * @return string
     */
    public function getTitle() 
    {
        return $this->name;
    }


        /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgidService()
    {
        return $this->hasOne(\open20\agid\service\models\AgidService::className(), ['id' => 'agid_service_id']);
    }
}
