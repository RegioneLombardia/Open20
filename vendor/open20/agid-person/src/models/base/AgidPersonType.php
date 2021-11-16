<?php

namespace open20\agid\person\models\base;

use Yii;

/**
* This is the base-model class for table "agid_person_type".
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
            * @property \backend\modules\operators\models\AgidPerson[] $agidPeople
    */
abstract class AgidPersonType extends \open20\amos\core\record\Record
{
    public $isSearch = false;

/**
* @inheritdoc
*/
public static function tableName()
{
return 'agid_person_type';
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
    'id' => Yii::t('agid-person', 'ID'),
    'name' => Yii::t('agid-person', 'Nome'),
    'description' => Yii::t('agid-person', 'Descrizione'),
    'created_at' => Yii::t('agid-person', 'Created at'),
    'updated_at' => Yii::t('agid-person', 'Updated at'),
    'deleted_at' => Yii::t('agid-person', 'Deleted at'),
    'created_by' => Yii::t('agid-person', 'Created by'),
    'updated_by' => Yii::t('agid-person', 'Updated by'),
    'deleted_by' => Yii::t('agid-person', 'Deleted by'),
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getAgidPeople()
    {
    return $this->hasMany(\open20\agid\person\models\AgidPerson::className(), ['agid_person_type_id' => 'id']);
    }
}
