<?php

namespace open20\agid\person\models\base;

use Yii;
use open20\agid\organizationalunit\models\AgidOrganizationalUnit;

/**
 * This is the base-model class for table "agid_person_organizational_unit_mm".
 *
 * @property integer $id
 * @property integer $agid_organizational_unit_id
 * @property integer $agid_person_id
 * @property string $role
 * @property string $function
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\agid\organizationalunit\models\AgidOrganizationalUnit $agidOrganizationalUnit
 * @property \open20\agid\person\models\AgidPerson $agidPerson
 */
abstract class AgidPersonOrganizationalUnitMm extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agid_person_organizational_unit_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agid_organizational_unit_id', 'agid_person_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['function'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['role'], 'string', 'max' => 255],
            [['agid_organizational_unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\agid\organizationalunit\models\AgidOrganizationalUnit::className(), 'targetAttribute' => ['agid_organizational_unit_id' => 'id']],
            [['agid_person_id'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\agid\person\models\AgidPerson::className(), 'targetAttribute' => ['agid_person_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('agid-person', 'ID'),
            'agid_organizational_unit_id' => Yii::t('agid-person', 'Agis Organizational Unit'),
            'agid_person_id' => Yii::t('agid-person', 'Agis Organizational Unit Father'),
            'role' => Yii::t('agid-person', 'Ruolo'),
            'function' => Yii::t('agid-person', 'Funzione'),
            'created_at' => Yii::t('agid-person', 'Created at'),
            'updated_at' => Yii::t('agid-person', 'Updated at'),
            'deleted_at' => Yii::t('agid-person', 'Deleted at'),
            'created_by' => Yii::t('agid-person', 'Created at'),
            'updated_by' => Yii::t('agid-person', 'Updated by'),
            'deleted_by' => Yii::t('agid-person', 'Deleted by'),
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
    public function getAgidPerson()
    {
        return $this->hasOne(\open20\agid\person\models\AgidPerson::className(), ['id' => 'agid_person_id']);
    }
}
