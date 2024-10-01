<?php

namespace open20\amos\utility\models\base;

use Yii;

/**
* This is the base-model class for table "auth_rule".
*
    * @property string $name
    * @property resource $data
    * @property integer $created_at
    * @property integer $updated_at
    *
            * @property \open20\amos\utility\models\AuthItem[] $authItems
    */
 class  AuthRule extends \open20\amos\core\record\Record
{
    public $isSearch = false;

/**
* @inheritdoc
*/
public static function tableName()
{
return 'auth_rule';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['name'], 'required'],
            [['data'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'name' => Yii::t('amosutility', 'Name'),
    'data' => Yii::t('amosutility', 'Data'),
    'created_at' => Yii::t('amosutility', 'Created At'),
    'updated_at' => Yii::t('amosutility', 'Updated At'),
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getAuthItems()
    {
    return $this->hasMany(\open20\amos\utility\models\AuthItem::className(), ['rule_name' => 'name']);
    }
}
