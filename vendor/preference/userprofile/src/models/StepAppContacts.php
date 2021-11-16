<?php

namespace preference\userprofile\models;

use Yii;
use yii\base\Model;
use yii\helpers\VarDumper;

/**
 * Model without table
 * 
 */
class StepAppContacts extends Model
{
    public $target_cittadini;
    public $target_impresa;
    public $target_enti_operatori;

    public function rules()
    {
        return [
            [['target_cittadini', 'target_impresa', 'target_enti_operatori'], 'string'],
            

        ];
    }

    public function attributeLabels()
    {
        return [
            'target_cittadini' => Yii::t('preferenceuser', 'Cittadini'),
            'target_impresa' => Yii::t('preferenceuser', 'Imprese'),
            'target_enti_operatori' => Yii::t('preferenceuser', 'Enti/operatori'),
        ];
    }
}
