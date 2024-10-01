<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    openinnovation\organizations\controllers
 * @category   CategoryName
 */

namespace open20\amos\events\models;

use open20\amos\events\AmosEvents;
use yii\base\Model;
use yii\web\UploadedFile;

class EventInvitationPartner extends Model
{

    public $name;
    public $surname;
    public $fiscal_code;
    public $email;

    public function rules()
    {
        return [
            // [['name', 'surname', 'fiscal_code', 'email'], 'required', 'when' => function($mod) {
            //     return $mod->name || $mod->surname || $mod->fiscal_code || $mod->email;
            // }],
            [['email'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['fiscal_code'], 'string', 'max' => 16],
            [['name', 'surname'], 'string', 'max' => 50],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'email' => AmosEvents::txt('#email'),
            'fiscal_code' => AmosEvents::txt('#fiscalcode'),
            'name' => AmosEvents::txt('#name'),
            'surname' => AmosEvents::txt('#surname'),
        ];
    }

}