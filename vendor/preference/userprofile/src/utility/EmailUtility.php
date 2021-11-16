<?php

namespace preference\userprofile\utility;

use open20\amos\admin\models\UserProfile;
use open20\amos\core\utilities\Email;
use open20\amos\cwh\models\CwhTagOwnerInterestMm;
use preference\userprofile\exceptions\NotificationEmailException;
use preference\userprofile\models\PreferenceChannel;
use preference\userprofile\models\Topic;
use preference\userprofile\models\PreferenceCrossTopicView;
use preference\userprofile\models\PreferenceTopicChannelMm;
use preference\userprofile\models\PreferenceUserTargetAttribute;
use preference\userprofile\models\Tag;
use preference\userprofile\Module;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\VarDumper;

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    preference\userprofile\utilty
 * @category   CategoryName
 */

/**
 * Description of TopicTagUtility
 *
 */
class EmailUtility
{

    /**
     * Undocumented function
     *
     * @param string $token
     * @param string $toEmail
     * @return boolean
     */
    public static function sendTargetMailValidationToken($token, $toEmail, $objectString, $targetLabel, $context = 'contact')
    {

        $mailModule = Yii::$app->getModule("email");
        $mailModule->defaultLayout = "layout_without_header_and_footer";
        $url = null;
        switch ($context) {
            case 'contact':
                $url = \Yii::$app->params['platform']['frontendUrl'] . '/preferenceuser/preference/validate-contact-email?token=' . $token;
                break;
            case 'registration':
                $url = \Yii::$app->params['platform']['frontendUrl'] . '/preferenceuser/preference/validate-registration-email?token=' . $token;
                break;
        }

        if (is_null($url)) {
            throw new NotificationEmailException('Impossibile generare l\'url di invio token');
        }


        if (is_string($targetLabel)) {
            $tags = [$targetLabel];
        } else {
            $tags = $targetLabel;
        }
        $htmlTag = '<table width="300" style="margin: 25px"><tbody style="padding: 50px 0;">';
        foreach ($tags as $tag) {
            $htmlTag .= "<tr>
            <td><img src=\"http://a4f6d9.emailsp.com/assets/1/Group%201452.png\" alt=\"\"></td>
            <td><span style=\"font-size: 22px; font-family: Arial;\"><strong><span style=\"color: #003354;\">$tag</span></strong></span></td>
            </tr>";
        }
        $htmlTag .= '</tbody></table>';

//        <a href="http://a4f6d9.emailsp.com/%7Btokenlink%7D" id="" style="font-size: 16px; line-height: 24px; text-align: center; color: #ffffff; background-color: #297438; border-radius: 4px; padding: 12px 24px; border: 1px solid #297438;">Conferma </a>
        $url = "<a href=\"http://[track]/".$url."\" id=\"\" style=\"font-size: 16px; line-height: 24px; text-align: center; color: #ffffff; background-color: #297438; border-radius: 4px; padding: 12px 24px; border: 1px solid #297438;\">Conferma </a>";

        $dataParse = [
            'tokenlink' => $url,
            'targetlabel' => $htmlTag,
            'email' => $toEmail,
        ];
        
        $ok = self::sendEmailGeneralMailup(Yii::$app->params['supportEmail'], $toEmail, $objectString, $dataParse, 24);

//        $ok  = Email::sendMail(
//            Yii::$app->params['supportEmail'],
//            $toEmail,
//            $objectString,
//            Yii::$app->controller->renderPartial(
//                '@vendor/preference/userprofile/src/email/2-verifica-email-contatto.php',
//                [
//                    'tokenLink' => $url,
//                    'targetLabel' => $targetLabel,
//                    'emailToValidate' => $toEmail,
//                ]
//            )
//        );
        if ($ok) {
            return true;
        } else {
            throw new NotificationEmailException('Impossibile inviare la comunicazione');
        }
    }

    /**
     * Undocumented function
     *
     * @param string $token
     * @param string $toEmail
     * @return boolean
     */
    public static function sendUserMailValidationToken($token, $toEmail, $objectString)
    {

        $mailModule = Yii::$app->getModule("email");
        $mailModule->defaultLayout = "layout_without_header_and_footer";
        $url = Url::toRoute(['/preferenceuser/preference/validate-registration-email', 'token' => $token], true);
        $dataParse = [
            'tokenlink' => $url,
            'email' => $toEmail,
        ];
        $ok = self::sendEmailGeneralMailup(Yii::$app->params['supportEmail'], $toEmail, $objectString, $dataParse, 23);

//        $ok  = Email::sendMail(
//            Yii::$app->params['supportEmail'],
//            $toEmail,
//            $objectString,
//            Yii::$app->controller->renderPartial(
//                '@vendor/preference/userprofile/src/email/1-verifica-email-autenticazione',
//                [
//                    'tokenLink' => $url,
//                    'emailToValidate' => $toEmail,
//                ]
//            )
//        );
        if ($ok) {
            return true;
        } else {
            throw new NotificationEmailException('Impossibile inviare la comunicazione');
        }
    }

    public static function sendUserMailValidationEmailOtp($otp, $toEmail, $objectString)
    {

        $mailModule = Yii::$app->getModule("email");
        $mailModule->defaultLayout = "layout_without_header_and_footer";
        $dataParse = [
            'otp' => $otp,
            'email' => $toEmail,
        ];
        $ok = self::sendEmailGeneralMailup(Yii::$app->params['supportEmail'], $toEmail, $objectString, $dataParse, 1125);
        if ($ok) {
            return true;
        } else {
            throw new NotificationEmailException('Impossibile inviare la comunicazione');
        }
    }

    /**
     * Undocumented function
     *
     * @param string $token
     * @param string $toEmail
     * @return boolean
     */
    // public static function sendUserMailRegistrationOk($toEmail, $objectString)
    // {
    //     $mailModule = Yii::$app->getModule("email");
    //     $mailModule->defaultLayout = "layout_without_header_and_footer";
    //     $ok  = Email::sendMail(
    //         Yii::$app->params['supportEmail'],
    //         $toEmail,
    //         $objectString,
    //         Yii::$app->controller->renderPartial(
    //             '../../email/4-verifica-email-autenticazione-final',
    //             [
    //                 'emailToValidate' => $toEmail,
    //             ]
    //         )
    //     );
    //     if ($ok) {
    //         return true;
    //     } else {
    //         throw new NotificationEmailException('Impossibile inviare la comunicazione');
    //     }
    // }


    /**
     * @param $toEmail
     * @param $objectString
     * @param $userName
     * @param $password
     * @return bool
     * @throws NotificationEmailException
     */
    public static function sendUserMailQuickRegistration($toEmail, $objectString, $userName, $password)
    {

        $mailModule = Yii::$app->getModule("email");
        $mailModule->defaultLayout = "layout_without_header_and_footer";

        $dataParse = [
            'username' => $userName,
            'password' => $password
        ];

        $ok = self::sendEmailGeneralMailup(Yii::$app->params['supportEmail'], $toEmail, $objectString, $dataParse, 12);

        if ($ok) {
            return true;
        } else {
            throw new NotificationEmailException('Impossibile inviare la comunicazione');
        }
    }

    /**
     * @param $from
     * @param $to
     * @param $subject
     * @param $dataParse
     * @param int $mailup_original_message_id
     * @return bool
     */
    public static function sendEmailGeneralMailup($from, $to, $subject, $dataParse, $mailup_original_message_id = null)
    {
        $newsletterModule = \Yii::$app->getModule('newsletter');
        $mailupListId = Yii::$app->params['mailup']['service-account']['list-id'];
        // configuro l'account locale
        $newsletterModule->username = Yii::$app->params['mailup']['service-account']['username'];
        $newsletterModule->password = Yii::$app->params['mailup']['service-account']['password'];
        $newsletterModule->client_id = Yii::$app->params['mailup']['service-account']['client_id'];
        $newsletterModule->client_secret = Yii::$app->params['mailup']['service-account']['client_secret'];

        if ($newsletterModule && !empty($mailup_original_message_id)) {
            // GET MAILUP TEMPLATE AND SUBTITUTION OF [CONTENT] WITH PLATFORM PERSONALIZED EMAIL TEXT
            $mailServiceClassname = $newsletterModule->mail_service_driver;
            $mailService = new $mailServiceClassname();
            $emailDecoded = $mailService->getEmail($mailupListId, $mailup_original_message_id);


            $htmlWithParams = self::parseEmailWithParams($emailDecoded->Content, $dataParse);
            $htmlWithParams = str_replace('http://[track]/', '', $htmlWithParams);
            $mailModule = \Yii::$app->getModule("email");
            if (isset($mailModule)) {
               
                $mailModule->defaultLayout = 'layout_without_header_and_footer';
                return $mailModule->send($from, $to, $subject, $htmlWithParams);
            }
        }
        return false;
    }


    /**
     * @param $text
     * @param $dataParse
     * @return string
     */
    public static function parseEmailWithParams($text, $dataParse)
    {
        $textWithParms = Module::t('preferenceuserprofile', $text, $dataParse);
        return $textWithParms;
    }
}
