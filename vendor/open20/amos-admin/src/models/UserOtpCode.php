<?php

namespace open20\amos\admin\models;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\utility\UserProfileMailUtility;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user_otp_code".
 */
class UserOtpCode extends \open20\amos\admin\models\base\UserOtpCode
{

    const TYPE_AUTH_EMAIL = 'email';
    public $auth_code;

    public function representingColumn()
    {
        return [
//inserire il campo o i campi rappresentativi del modulo
        ];
    }

    public function attributeHints()
    {
        return [
        ];
    }

    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute)
    {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['auth_code', 'safe']
        ]);
    }

    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(),
                [
                ]);
    }


    public static function getEditFields()
    {
        $labels = self::attributeLabels();

        return [
            [
                'slug' => 'user_id',
                'label' => $labels['user_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'session_id',
                'label' => $labels['session_id'],
                'type' => 'string'
            ],
            [
                'slug' => 'otp_code',
                'label' => $labels['otp_code'],
                'type' => 'string'
            ],
            [
                'slug' => 'type',
                'label' => $labels['type'],
                'type' => 'string'
            ],
            [
                'slug' => 'expire',
                'label' => $labels['expire'],
                'type' => 'datetime'
            ],
        ];
    }

    /**
     * @return string marker path
     */
    public function getIconMarker()
    {
        return null; //TODO
    }

    /**
     * If events are more than one, set 'array' => true in the calendarView in the index.
     * @return array events
     */
    public function getEvents()
    {
        return NULL; //TODO
    }

    /**
     * @return url event (calendar of activities)
     */
    public function getUrlEvent()
    {
        return NULL; //TODO e.g. Yii::$app->urlManager->createUrl([]);
    }

    /**
     * @return color event
     */
    public function getColorEvent()
    {
        return NULL; //TODO
    }

    /**
     * @return title event
     */
    public function getTitleEvent()
    {
        return NULL; //TODO
    }


    /**
     * Send email with the authentication code
     * @param $email
     * @param $subject
     * @param $text
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public static function sendEmailAuthentication($email, $subject, $mainText, $user = null)
    {
        if (!empty($email)) {
            $code = (string)rand(10000, 99999);
            $id_session = \Yii::$app->session->getId();

            if (!empty($user)) {
                $authentication = UserOtpCode::find()
                    ->andWhere(['user_id' => $user->id])->one();
            }else{
                $authentication = UserOtpCode::find()
                    ->andWhere(['session_id' => $id_session])->one();
            }




            if (empty($authentication)) {
                $authentication = new UserOtpCode();
            }

            $expireDate = new \DateTime();
            $expireDate->modify('+5 minutes');

            $authentication->session_id = $id_session;
            if (!empty($user)) {
                $authentication->user_id = $user->id;
            }

            $authentication->type = UserOtpCode::TYPE_AUTH_EMAIL;
            $authentication->otp_code = $code;
            $authentication->expire = $expireDate->format('Y-m-d H:i:s');

            $authentication->save();

            $text = $mainText;
            $text .= "<p>" . AmosAdmin::t('amosadmin', "Inserisci il seguente codice OTP e clicca sul bottone 'Conferma OTP'.<br> <strong>Codice OTP: </strong>{code} <br>Hai a disposizione 5 minuti per completare l'operazione.", [
                    'code' => $authentication->otp_code
                ]) . "</p>";
//            $text .= "<p><strong>".AmosAdmin::t('amosadmin', "Codice OTP:")."</strong>"." ".$authentication->otp_code."</p>";


            if (UserProfileMailUtility::sendEmailGeneral($email, null, $subject, $text)) {
                return true;
//                Yii::$app->getSession()->addFlash('sucess', Yii::t('app', 'Email correttamente inviata'));
            } else {
                return false;
//                Yii::$app->getSession()->addFlash('danger', Yii::t('app', 'Notifica non inviata, profilo richiedente rettifica non trovato'));
            }
        }
        return false;
    }

    /**
     * @param $code
     * @param $type
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public static function isExpired($code, $type)
    {
        $id_session = \Yii::$app->session->getId();
        $authentication = UserOtpCode::find()
            ->andWhere([
                'session_id' => $id_session,
                'type' => $type,
                'otp_code' => $code])->one();

        $isExpired = false;
        $expireDate = new \DateTime($authentication->expire);
        $nowDate = new \DateTime();
        if ($nowDate > $expireDate) {
            $isExpired = true;
        }
        return $isExpired;
    }

    /**
     * Check if the authentication code is correct
     *
     * @param $codice
     * @param $tipoAuth
     * @return bool
     */
    public static function isValidCodice($code, $type, $user_id = null)
    {
        $id_session = \Yii::$app->session->getId();
        if (!empty($user_id)) {
            $authentication = UserOtpCode::find()
                ->andWhere([
                    'user_id' => $user_id,
                    'type' => $type,
                    'otp_code' => $code])->one();

        } else {
            $authentication = UserOtpCode::find()
                ->andWhere([
                    'session_id' => $id_session,
                    'type' => $type,
                    'otp_code' => $code])->one();
        }

        $isExpired = false;
        $expireDate = new \DateTime($authentication->expire);
        $nowDate = new \DateTime();
        if ($nowDate > $expireDate) {
            $isExpired = true;
        }

        if (!empty($authentication)) {
            return true;
        } else {
            return false;
        }
    }
}
