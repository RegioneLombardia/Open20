<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\basic\template
 * @category   CategoryName
 */

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 * @package frontend\models
 */
class ContactForm extends Model
{
    /**
     * @var string Name
     */
    public $name;

    /**
     * @var string Email address
     */
    public $email;

    /**
     * @var string Email subject
     */
    public $subject;

    /**
     * @var string Email body
     */
    public $body;

    /**
     * @var string Captcha
     */
    public $verifyCode;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'subject', 'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],

            ['fiscal_code', 'required'],
            ['fiscal_code', 'checkCodiceFiscale'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param string $email the target email address
     * @return boolean whether the email was sent
     */
    public function sendEmail($email)
    {
        return Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([$this->email => $this->name])
            ->setSubject($this->subject)
            ->setTextBody($this->body)
            ->send();
    }


    /**
     * @param string $attribute
     * @param array $params
     */
    public function checkCodiceFiscale($attribute, $params)
    {
        $codiceFiscale = $this->$attribute;
        if (!$codiceFiscale) {
            $isValid = true;
        } // se non può essere null se ne deve occupare qualcun altro
        if (strlen($codiceFiscale) != 16) {
            $isValid = false;
        } else {
            $codiceFiscale = strtoupper($codiceFiscale);
            if (!preg_match("/^[A-Z0-9]+$/", $codiceFiscale)) {
                $isValid = false;
            }
            $s = 0;
            for ($i = 1; $i <= 13; $i += 2) {
                $c = $codiceFiscale[$i];
                if ('0' <= $c && $c <= '9') $s += ord($c) - ord('0');
                else $s += ord($c) - ord('A');
            }
            for ($i = 0; $i <= 14; $i += 2) {
                $c = $codiceFiscale[$i];
                switch ($c) {
                    case '0':
                        $s += 1;
                        break;
                    case '1':
                        $s += 0;
                        break;
                    case '2':
                        $s += 5;
                        break;
                    case '3':
                        $s += 7;
                        break;
                    case '4':
                        $s += 9;
                        break;
                    case '5':
                        $s += 13;
                        break;
                    case '6':
                        $s += 15;
                        break;
                    case '7':
                        $s += 17;
                        break;
                    case '8':
                        $s += 19;
                        break;
                    case '9':
                        $s += 21;
                        break;
                    case 'A':
                        $s += 1;
                        break;
                    case 'B':
                        $s += 0;
                        break;
                    case 'C':
                        $s += 5;
                        break;
                    case 'D':
                        $s += 7;
                        break;
                    case 'E':
                        $s += 9;
                        break;
                    case 'F':
                        $s += 13;
                        break;
                    case 'G':
                        $s += 15;
                        break;
                    case 'H':
                        $s += 17;
                        break;
                    case 'I':
                        $s += 19;
                        break;
                    case 'J':
                        $s += 21;
                        break;
                    case 'K':
                        $s += 2;
                        break;
                    case 'L':
                        $s += 4;
                        break;
                    case 'M':
                        $s += 18;
                        break;
                    case 'N':
                        $s += 20;
                        break;
                    case 'O':
                        $s += 11;
                        break;
                    case 'P':
                        $s += 3;
                        break;
                    case 'Q':
                        $s += 6;
                        break;
                    case 'R':
                        $s += 8;
                        break;
                    case 'S':
                        $s += 12;
                        break;
                    case 'T':
                        $s += 14;
                        break;
                    case 'U':
                        $s += 16;
                        break;
                    case 'V':
                        $s += 10;
                        break;
                    case 'W':
                        $s += 22;
                        break;
                    case 'X':
                        $s += 25;
                        break;
                    case 'Y':
                        $s += 24;
                        break;
                    case 'Z':
                        $s += 23;
                        break;
                }
            }
            if (isset($codiceFiscale[15])) {

                if (chr($s % 26 + ord('A')) != $codiceFiscale[15]) {
                    $isValid = false;
                } else {
                    $isValid = true;
                }
            }
        }
        if (!$isValid) {
            $this->addError($attribute, Yii::t('preferenceuser', 'Il codice fiscale non è in un formato consentito'));
        }
    }


}
