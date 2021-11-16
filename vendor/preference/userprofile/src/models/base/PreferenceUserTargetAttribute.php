<?php

namespace preference\userprofile\models\base;

use open20\amos\core\record\Record;
use open20\amos\core\user\User;
use preference\userprofile\exceptions\NotificationEmailException;
use preference\userprofile\utility\EmailUtility;
use preference\userprofile\utility\TargetAttributeUtility;
use preference\userprofile\utility\TargetTagUtility;
use preference\userprofile\utility\UserInterestTagUtility;
use Yii;
use yii\helpers\VarDumper;
use  common\components\MailupTransactionalSMS;
use Exception;

/**
 * This is the base-model class for table "preference_user_target_attribute".
 *
 * @property integer $id
 * @property string $email
 * @property string $email_validation_token
 * @property integer $validated_email_flag
 * @property string $phone
 * @property string $phone_validation_token
 * @property integer $validated_phone_flag
 * @property string $target_code
 * @property integer $user_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */
class PreferenceUserTargetAttribute extends Record
{
    const EVENT_EMAIL_MODIFIED = 'email-modified';
    const EVENT_PHONE_MODIFIED = 'phone-modified';

    public $isSearch = false;
    public $sendEmailValidationComunication = true;

    public function init()
    {
        parent::init();

        $this->on(self::EVENT_EMAIL_MODIFIED, [$this, 'emailModified']);
        $this->on(self::EVENT_PHONE_MODIFIED, [$this, 'phoneModified']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'preference_user_target_attribute';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['validated_email_flag', 'validated_phone_flag', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['email', 'phone', 'email_validation_token', 'phone_validation_token'], 'string', 'max' => 256],
            [['user_id'], 'exist', 'skipOnError' => false, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('preferenceuser', 'ID'),
            'email' => Yii::t('preferenceuser', 'Email'),
            'validated_email_flag' => Yii::t('preferenceuser', 'Validated email flag'),
            'phone' => Yii::t('preferenceuser', 'Cellulare'),
            'validated_phone_flag' => Yii::t('preferenceuser', 'Validated phone flag'),
            'created_at' => Yii::t('preferenceuser', 'Created at'),
            'updated_at' => Yii::t('preferenceuser', 'Updated at'),
            'deleted_at' => Yii::t('preferenceuser', 'Deleted at'),
            'created_by' => Yii::t('preferenceuser', 'Created by'),
            'updated_by' => Yii::t('preferenceuser', 'Updated by'),
            'deleted_by' => Yii::t('preferenceuser', 'Deleted by'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Undocumented function
     *
     * @param [type] $event
     * @return void
     */
    public function beforeSave($event)
    {
        // VarDumper::dump( $this->dirtyAttributes, $depth = 10, $highlight = true);
        try {
            if (isset($this->dirtyAttributes['email'])) {
                $this->trigger(self::EVENT_EMAIL_MODIFIED);
            }
        } catch (NotificationEmailException $e) {
            $this->addError('email', Yii::t('preferenceuser', 'Email non modificata. Impossibile inviare la comunicazione...'));
            return false;
        }

        try {
            if (isset($this->dirtyAttributes['phone'])) {
                $this->trigger(self::EVENT_PHONE_MODIFIED);
            }
        } catch (Exception $e) {
            $this->addError('phone', Yii::t('preferenceuser', 'Numero cellulare non modificato. Impossibile inviare SMS di verifica ...'));
            // VarDumper::dump(  $e->getTrace(), $depth = 3, $highlight = true); die;
            return false;
        }

        return parent::beforeSave($event);
    }

    public function emailModified($event)
    {
        if (!empty($this->email)) {
            $this->validated_email_flag = 0;
            $this->email_validation_token = TargetAttributeUtility::generateNewEmailToken();
            $tag = TargetTagUtility::getTargetByCode($this->target_code);
            if ($this->sendEmailValidationComunication) {
                EmailUtility::sendTargetMailValidationToken($this->email_validation_token
                    , $this->email
                    , 'Lombardia Informa: validazione email di contatto'
                    , $tag->nome);
            }
        } else {
            // mi possono impostare una mail vuota, svalido il flag e annullo il token
            $this->validated_email_flag = 0;
            $this->email_validation_token = null;
        }
    }

    public function phoneModified()
    {
        if (!empty($this->phone)) {
            $this->validated_phone_flag = 0;
            $this->phone_validation_token = TargetAttributeUtility::generateNewPhoneToken();

            $mailupSms = new MailupTransactionalSMS();
            $mailupSms->setContent('Lombardia Informa: il codice di verifica per il numero di telefono [Telefono] attivato su [Target] è ' . $this->phone_validation_token);
            $mailupSms->setRecipient($this->phone);
            $mailupSms->setCampaignCode('phone-validation-token');
            $mailupSms->setDynamicFields([
                [
                    'N' => 'Telefono',
                    'V' => $this->phone,
                ],
                [
                    'N' => 'Target',
                    'V' => strtoupper(TargetTagUtility::getTargetByCode($this->target_code)->nome),
                ],
            ]);
            // TODO Se vuoi si può memorizzare l'id di invio...
            $ret = $mailupSms->sendSms();
        } else {
            // mi possono impostare un numero vuoto, svalido il flag e annullo il token
            $this->validated_phone_flag = 0;
            $this->phone_validation_token = null;
        }
    }

}
