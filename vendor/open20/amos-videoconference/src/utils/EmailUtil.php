<?php
namespace open20\amos\videoconference\utils;

use open20\amos\community\models\Community;
use open20\amos\core\helpers\Html;
use open20\amos\videoconference\AmosVideoconference;
use open20\amos\videoconference\models\Videoconf;
use yii\helpers\Console;
use yii\helpers\Url;
use open20\amos\core\utilities\Email;


class EmailUtil
{
    /**
     * manda la mail agli utenti collegati alla videoconferenza
     *
     * @param $videoconference Videoconf
     * @param $user
     * @return bool
     */
    public static function sendEmailPartecipant($videoconference, $user)
    {
        $from = \Yii::$app->params['email-assistenza'];
        $to = [$user->email];
        $subject = 'Invito videoconferenza';
        $url_videoconf = $videoconference->getLinkToVideoconf();
        $url_community = \Yii::$app->params['platform']['frontendUrl'] . '/community/join/open-join?id=' . $videoconference->community_id;
        $body []=  "<br>";

        if($videoconference->status == Videoconf::STATUS_FUTURE) {
            $body []=  AmosVideoconference::t('amosvideoconference', "sei invitato a partecipare alla videoconferenza ")
                . "<a href='" . $url_videoconf . "'>" .  $videoconference->title . "</a>"
                . AmosVideoconference::t('amosvideoconference', " della community")
                . " <i><u>"
                . "<a href='" . $url_community . "'>" . Community::findOne($videoconference->community_id)->name . "</a>"
                . "</u></i> "
                . AmosVideoconference::t('amosvideoconference', "a cui partecipi, che avrà inizio il giorno")
                . " "
                . \Yii::$app->formatter->asDate($videoconference->begin_date_hour)
                . " alle ore "
                . \Yii::$app->formatter->asTime($videoconference->begin_date_hour, 'H:mm');
        }else {
            $body []= AmosVideoconference::t('amosvideoconference', "Ti inviamo il link per accedere alla videoconferenza");
            $body []= "<a href='". $url_videoconf ."'>" . AmosVideoconference::t('amosvideoconference', 'Link di accesso alla videoconferenza') . "</a>";
            $body []= AmosVideoconference::t('amosvideoconference', "Della community") . " <i><u><a href='" . $url_community . "'>" . Community::findOne($videoconference->community_id)->name . "</a></u></i>";;
            $body []= AmosVideoconference::t('amosvideoconference', 'Se il link non funziona copia e incolla il seguente in una finestra del tuo browser di navigazione');
            $body []= "<a href='". $url_videoconf ."'>" .$url_videoconf. "</a>";
        }

        $params = [
            'userProfile' => $user->userProfile->toArray(),
            'body' => $body,
            'category' => "amosvideoconference",
            'subject' => $subject,
            'profile' => $user->userProfile
//            'videoconference' => $videoconference->toArray(),
//            'url_videoconf' => $url_videoconf
        ];
        $template = '@vendor/open20/amos-videoconference/src/mail/generic/generic-html';
        $templateSubject = '@vendor/open20/amos-videoconference/src/mail/generic/subject-html';

        $sent = self::sendEmail($to, $from, $subject, $params, $template, $templateSubject, $user);
        return $sent;
    }

    /**
     * manda la mail dipropmemoria agli utenti collegati alla videoconferenza
     *
     * @param $videoconference Videoconf
     * @param $user
     * @return bool
     */
    public static function sendNotification($videoconference, $user){
        $url_community = \Yii::$app->params['platform']['frontendUrl'] . '/community/join/open-join?id=' . $videoconference->community_id;
        $url = Url::to(['/videoconference/videoconf/index'], true);
        $from = \Yii::$app->params['email-assistenza'];
        $to = [$user->email];
        $subject = 'Notifica promemoria inizio videoconferenza';
        $body []= AmosVideoconference::t("amosvideoconference", "Ti ricordiamo che sarà possibile accedere alla videoconferenza della community")
            . " <i><u><a href='" . $url_community . "'>" . Community::findOne($videoconference->community_id)->name . "</a></u></i>";
        $body []= AmosVideoconference::t('amosvideoconference', 'alle')
            . " <strong>"
            . \Yii::$app->formatter->asDate($videoconference->begin_date_hour)
            . " alle "
            . \Yii::$app->formatter->asTime($videoconference->begin_date_hour, 'H:mm')
            ."</strong>";
        $body []= AmosVideoconference::t('amosvideoconference', "Ti inviamo il link per accedere alla videoconferenza");
        $body []= AmosVideoconference::t('amosvideoconference', "<a href='". $url ."'>".$url ."</a>");

        $params = [
            'userProfile' => $user->userProfile->toArray(),
            'body' => $body,
            'category' => "amosvideoconference",
            'subject' => $subject,
            'profile' => $user->userProfile
        ];
        $template = '@vendor/open20/amos-videoconference/src/mail/generic/generic-html';
        $templateSubject = '@vendor/open20/amos-videoconference/src/mail/generic/subject-html';

        $sent = self::sendEmail($to, $from, $subject, $params, $template, $templateSubject, $user);
        return $sent;
    }

    /**
     * 
     * @param type $to
     * @param type $from
     * @param type $subject
     * @param type $params
     * @param type $template
     * @param type $templateSubject
     * @param type $user
     */
    public static function sendEmail($to, $from, $subject, $params, $template, $templateSubject, $user){
       
        try {
            $subject = Email::renderMailPartial($templateSubject,$params, $user->id);
            $message = Email::renderMailPartial($template, $params, $user->id);
            $email   = new Email();
            $email->sendMail($from, $to, $subject, $message);
                
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
            return false;
        }
        return true;
    }


}