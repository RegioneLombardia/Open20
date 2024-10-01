<?php

namespace app\models;

use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FinalForm extends Model implements \open20\amos\wizflow\WizflowModelInterface
{
    public $rate;
    public $status;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['rate'], 'required'],
        ];
    }

    public function summary()
    {
        return 'this is : ' . $this->rate;
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'rate' => 'Did you like your wizflow experience ?',
        ];
    }
}
