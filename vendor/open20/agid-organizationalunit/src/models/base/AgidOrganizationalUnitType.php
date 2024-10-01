<?php

namespace open20\agid\organizationalunit\models\base;

use Yii;

/**
 * This is the base-model class for table "agid_organizational_unit_type".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\agid\organizationalunit\models\AgidOrganizationalUnit[] $agidOrganizationalUnits
 */
class AgidOrganizationalUnitType extends \yii\db\ActiveRecord
{
    public $isSearch = false;

	/**
	 * @inheritdoc
	 */
    public static function tableName()
    {
        return 'agid_organizational_unit_type';
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
            [['name'], 'string', 'max' => 255],
        ];
    }

	/**
	 * @inheritdoc
	 */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosorganizationalunit', 'ID'),
            'name' => Yii::t('amosorganizationalunit', 'Name'),
            'description' => Yii::t('amosorganizationalunit', 'Description'),
            'created_at' => Yii::t('amosorganizationalunit', 'Created at'),
            'updated_at' => Yii::t('amosorganizationalunit', 'Updated at'),
            'deleted_at' => Yii::t('amosorganizationalunit', 'Deleted at'),
            'created_by' => Yii::t('amosorganizationalunit', 'Created by'),
            'updated_by' => Yii::t('amosorganizationalunit', 'Updated by'),
            'deleted_by' => Yii::t('amosorganizationalunit', 'Deleted by'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgidOrganizationalUnits()
    {
        return $this->hasMany(\open20\agid\organizationalunit\models\AgidOrganizationalUnit::className(), ['agid_organizational_unit_type_id' => 'id']);
    }
}
