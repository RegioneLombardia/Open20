<?php

namespace open20\agid\organizationalunit\models\base;

use Yii;

use open20\amos\documenti\models\Documenti;
use open20\agid\organizationalunit\models\AgidOrganizationalUnit;

/**
 * This is the base-model class for table "agid_organizational_unit_documenti_mm".
 *
 * @property integer $id
 * @property integer $agid_organizational_unit_id
 * @property integer $documenti_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \app\models\AgidOrganizationalUnit $agidOrganizationalUnit
 * @property \app\models\Documenti $documenti
 */
class AgidOrganizationalUnitDocumentiMm extends \open20\amos\core\record\Record
{
    public $isSearch = false;

	/**
	 * @inheritdoc
	 */
    public static function tableName()
    {
        return 'agid_organizational_unit_documenti_mm';
    }

	/**
	 * @inheritdoc
	 */
    public function rules()
    {
        return [
            [['agid_organizational_unit_id', 'documenti_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['agid_organizational_unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => AgidOrganizationalUnit::className(), 'targetAttribute' => ['agid_organizational_unit_id' => 'id']],
            [['documenti_id'], 'exist', 'skipOnError' => true, 'targetClass' => Documenti::className(), 'targetAttribute' => ['documenti_id' => 'id']],
        ];
    }

	/**
	 * @inheritdoc
	 */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('app', 'ID'),
            'agid_organizational_unit_id' => Module::t('app', 'Agid Organizational Unit'),
            'documenti_id' => Module::t('app', 'Documenti'),
            'created_at' => Module::t('app', 'Created at'),
            'updated_at' => Module::t('app', 'Updated at'),
            'deleted_at' => Module::t('app', 'Deleted at'),
            'created_by' => Module::t('app', 'Created at'),
            'updated_by' => Module::t('app', 'Updated by'),
            'deleted_by' => Module::t('app', 'Deleted by'),
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
    public function getDocumenti()
    {
        return $this->hasOne(\open20\amos\documenti\models\Documenti::className(), ['id' => 'documenti_id']);
    }
}
