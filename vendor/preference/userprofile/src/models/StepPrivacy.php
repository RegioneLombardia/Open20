<?php
namespace preference\userprofile\models;

use Yii;
use yii\base\Model;

/**
 * Model without table
 * 
 */
class StepPrivacy extends Model
{
    public $privacy;

    public function rules()
    {
        return [
            [['privacy'], 'required'],
            [['privacy'], 'compare', 'compareValue' => 'on', 'message' => 'Selezionare la privacy'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'privacy' => Yii::t('preferenceuser', 'Accetto i termini e le condizioni privacy'),
        ];
    }

}