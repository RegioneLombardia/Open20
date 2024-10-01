<?php

namespace amos\userauth\models;

use amos\userauth\frontend\Module;
use open20\amos\core\record\Record;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class CredentialRequestForm
 *
 * This is the base-model class for table "credential_request".
 *
 * @property integer $id
 * @property string $nome
 * @property string $cognome
 * @property string $motivazione
 * @property string $email
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @package amos\userauth\models
 */

class CredentialRequestForm extends Record
{
    /**
     * @var int $privacy
     */
    public $privacy;

    /**
     * @var string $captcha
     */
    public $reCaptcha;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'credential_request';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => \Yii::t('userauth', 'Id'),
            'nome' => \Yii::t('userauth', 'Nome'),
            'cognome' => \Yii::t('userauth', 'Cognome'),
            'motivazione' => \Yii::t('userauth', 'Motivazioni della richiesta di credenziali'),
            'email' => \Yii::t('userauth', 'Email'),
            'created_at' => \Yii::t('userauth', 'Creato il'),
            'updated_at' => \Yii::t('userauth', 'Aggiornato il'),
            'deleted_at' => \Yii::t('userauth', 'Cancellato il'),
            'created_by' => \Yii::t('userauth', 'Creato da'),
            'updated_by' => \Yii::t('userauth', 'Aggiornato da'),
            'deleted_by' => \Yii::t('userauth', 'Cancellato da')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = ArrayHelper::merge(
            parent::rules(),
            [
                [['created_by', 'updated_by', 'deleted_by'], 'integer'],
                [['created_at', 'updated_at', 'deleted_at'], 'safe'],
                [['motivazione'], 'string'],
                [['nome', 'cognome', 'email'], 'string', 'max' => 255],
                [['nome', 'cognome', 'email'], 'required'],
            ]
        );

        return $rules;
    }
}