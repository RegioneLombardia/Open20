<?php

namespace open20\agid\organizationalunit\models\base;

use Yii;

/**
 * This is the base-model class for table "agid_organizational_organizational_unit_mm".
 *
 * @property integer $id
 * @property integer $agid_organizational_unit_id
 * @property integer $agid_organizational_unit_father_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class AgidOrganizationalOrganizationalUnitMm extends \open20\amos\core\record\Record
{
    public $isSearch = false;

	/**
	 * @inheritdoc
	 */
    public static function tableName()
    {
        return 'agid_organizational_organizational_unit_mm';
    }

	/**
	 * @inheritdoc
	 */
    public function rules()
    {
        return [
            [['agid_organizational_unit_id', 'agid_organizational_unit_father_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
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
            'agid_organizational_unit_id' => Yii::t('amosorganizationalunit', 'Agis Organizational Unit'),
            'agid_organizational_unit_father_id' => Yii::t('amosorganizationalunit', 'Agis Organizational Unit Father'),
            'created_at' => Yii::t('amosorganizationalunit', 'Created at'),
            'updated_at' => Yii::t('amosorganizationalunit', 'Updated at'),
            'deleted_at' => Yii::t('amosorganizationalunit', 'Deleted at'),
            'created_by' => Yii::t('amosorganizationalunit', 'Created at'),
            'updated_by' => Yii::t('amosorganizationalunit', 'Updated by'),
            'deleted_by' => Yii::t('amosorganizationalunit', 'Deleted by'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgidOrganizationalUnit()
    {
        return $this->hasOne(\open20\agid\organizationalunit\models\AgidOrganizationalUnit::className(), ['id' => 'agid_organizational_unit_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgidFatherOrganizationalUnit()
    {
        return $this->hasOne(\open20\agid\organizationalunit\models\AgidOrganizationalUnit::className(), ['id' => 'agid_organizational_unit_father_id']);
    }
}
