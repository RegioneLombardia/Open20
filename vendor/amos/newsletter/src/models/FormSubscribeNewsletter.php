<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 23/10/2018
 * Time: 17:58
 */

namespace amos\newsletter\models;


use amos\newsletter\Module;
use yii\base\Model;

class FormSubscribeNewsletter extends Model
{
    public $email;
    public $privacy;
    public $name;
    public $fields;
    public $idgroups;
    public $redirect_url;
    public $confirmEmail;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['email', 'privacy'], 'required'],
            ['idgroups', 'each', 'rule' => ['integer']],
            [['privacy'], 'required', 'requiredValue' => 1, 'message' => \Yii::t('app', 'E\' necessario acconsentire al trattamento dei dati personali')],
            ['email', 'email'],
            ['name', 'string'],
            [['idgroups','confirmEmail'], 'safe'],
            [['redirect_url','fields'], 'safe']
        ];
    }

    /**
     * Custom validation form "privacy" field
     */
    public function checkPrivacy()
    {
        if (!$this->privacy) {
            $this->addError('privacy', Module::t('amosnewsletter', "It's mandatory to accept the privacy notice before save"));
        }
    }


    public function attributeLabels()
    {
        return [
            'name' => Module::t('amosnewsletter', 'Name')
        ];
    }
}