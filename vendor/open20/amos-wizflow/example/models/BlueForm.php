<?php

namespace app\models;

use yii\base\Model;
use yii\helpers\Html;

/**
 * ContactForm is the model behind the contact form.
 */
class BlueForm extends Model implements \open20\amos\wizflow\WizflowModelInterface
{
    public $blueStuff;
    public $status;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['blueStuff'], 'required'],
        ];
    }

    public function summary()
    {
        return 'blue like : ' . Html::encode($this->blueStuff);
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'greenStuff' => 'name somthing blue',
        ];
    }
}
