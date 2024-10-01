<?php

namespace app\models;

use raoul2000\workflow\validation\WorkflowScenario;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class Step1Form extends Model implements \open20\amos\wizflow\WizflowModelInterface
{
    public $favoriteColor;
    public $status;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['favoriteColor'], 'required'],

            [['favoriteColor'], 'compare', 'compareValue' => 'blue', 'operator' => '==',
                'on' => WorkflowScenario::enterStatus('Wizflow/blue')],

            [['favoriteColor'], 'compare', 'compareValue' => 'green', 'operator' => '==',
                'on' => WorkflowScenario::enterStatus('Wizflow/green')],
        ];
    }

    public function summary()
    {
        return 'your favorite color is ' . $this->favoriteColor;
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'favoriteColor' => 'your favorite color',
        ];
    }
}
