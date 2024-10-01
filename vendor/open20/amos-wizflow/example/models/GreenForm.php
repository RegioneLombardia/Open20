<?php

namespace app\models;

use yii\base\Model;
use yii\helpers\Html;

/**
 * ContactForm is the model behind the contact form.
 */
class GreenForm extends Model implements \open20\amos\wizflow\WizflowModelInterface
{
    public $greenStuff;
    public $status;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['greenStuff'], 'required'],
        ];
    }

    public function summary()
    {
        return 'green like : ' . Html::encode($this->greenStuff);
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'greenStuff' => 'name somthing green',
        ];
    }
}
