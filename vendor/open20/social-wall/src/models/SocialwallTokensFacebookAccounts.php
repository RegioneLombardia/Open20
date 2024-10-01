<?php

namespace open20\socialwall\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * This is a dummy model class.
 */
class SocialwallTokensFacebookAccounts extends Model
{
    public $isNewRecord = false;

    public $id;
    public $access_token;
    public $name;
    public $expire_date;
    public $selected;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'selected'], 'integer'],
            [['access_token', 'name', 'expire_date'], 'string'],
            [['id', 'access_token', 'name', 'expire_date', 'selected'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('socialwall', 'ID'),
            'name' => Yii::t('socialwall', 'Nome'),
            'access_token' => Yii::t('socialwall', 'Access Token'),
            'expire_date' => Yii::t('socialwall', 'Data scadenza token'),
            'selected' => Yii::t('socialwall', 'Seleziona'),
        ];
    }

    public function representingColumn()
    {
        return [
//inserire il campo o i campi rappresentativi del modulo
        ];
    }

    public function attributeHints(){
        return [
        ];
    }

    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute) {
        return "null";
    }

}
