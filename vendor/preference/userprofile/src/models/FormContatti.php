<?php
namespace preference\userprofile\models;

use open20\amos\admin\AmosAdmin;
use Yii;
use yii\base\Model;

/**
 * Model without table
 * 
 */
class FormContatti extends Model
{
    public $elencoTipiMessaggio = [
        1 => 'Richiesta informazioni',
        2 => 'Segnalazione malfunzionamento',
        3 => 'Opinione sul servizio',
        4 => 'Suggerimento / proposta',
        5 => 'Altro',
    ];
    public $nome;
    public $cognome;
    public $email;
    public $confermaEmail;
    public $telefono;
    public $tipoMessaggio;
    public $messaggio;
    public $privacy;
    public $reCaptcha;

    public function rules()
    {
        return [
            [['nome', 'cognome', 'email', 'confermaEmail', 'messaggio', 'privacy'], 'required'],
            [['email', 'confermaEmail'], 'email'],
            [['messaggio'], 'string', 'max' => 1000],
            ['confermaEmail', 'compare', 'compareAttribute' => 'email', 'message' => Yii::t('preferenceuser', 'Le email inserite non coincidono')],
            [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator::className(), 'message' => AmosAdmin::t('amosadmin', "#register_recaptcha_alert")]
        ];
    }

    public function attributeLabels()
    {
        return [
            'privacy' => Yii::t('preferenceuser', 'Ho preso visione dell\'informativa privacy'),
            'email' => Yii::t('preferenceuser', 'Email'),
            'confermaEmail' => Yii::t('preferenceuser', 'Conferma Email'),
            'tipoMessaggio' => Yii::t('preferenceuser', 'Tipo messaggio'),
            'messaggio' => Yii::t('preferenceuser', 'Messaggio'),
            'nome' => Yii::t('preferenceuser', 'Nome'),
            'cognome' => Yii::t('preferenceuser', 'Cognome'),
        ];
    }

}