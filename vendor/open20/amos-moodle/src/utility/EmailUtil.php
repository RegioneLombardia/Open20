<?php

namespace open20\amos\moodle\utility;

use open20\amos\moodle\models\MoodleUser;
use open20\amos\moodle\AmosMoodle;
use open20\amos\core\user\User;
use open20\amos\core\utilities\Email;

use Yii;

class EmailUtil extends Email {

    /**
     * Manda la mail di richiesta iscrizione ad un corso chiuso di Moodle. 
     * La manda a tutti i MOODLE_ADMIN
     *
     * @param type $course Moodle Course
     * @param type $userId
     * @return bool
     */
    public static function sendEmailEnrolInClosedCourse($course, $userId = null) {
        $from = \Yii::$app->params['supportEmail'];
        $emailMoodleAdmin = MoodleUtility::getAllMoodleAdminUsersEmail();

        $to = $emailMoodleAdmin;

        if ($userId == null) {
            $loggedUser = \Yii::$app->getUser()->identity;
            $loggedUserId = $loggedUser->id;
        } else {
            $loggedUserId = $userId;
        }

        $moodleUser = MoodleUser::findOne([
            'user_id' => $loggedUserId,
        ]);
        
        $infoMoodleUser = $moodleUser->moodle_name 
            . ' '
            . $moodleUser->moodle_surname
            . ' '
            . $moodleUser->moodle_email
            . ' (ID Moodle.'
            . $moodleUser->moodle_userid
            . ')';

        $subject = AmosMoodle::t("amosmoodle", '#subscription_request_to_closed_course');

        $body [] = AmosMoodle::t('amosmoodle', '#enrollment_request_sent');
        $body [] = AmosMoodle::t('amosmoodle', '#moodle_course_details', [
            'name' => $course->name,
            'moodle_courseid' => $course->moodle_courseid
        ]);

        $body [] = AmosMoodle::t('amosmoodle', '#moodle_requested_by', [
            'infoMoodleUser' => $infoMoodleUser
        ]);

        $params = [
            'body' => $body,
        ];

        $layout = '@vendor/open20/amos-moodle/src/mail/generic/generic-html';

        return self::sendEmail($from, $to, $subject, $params, $layout, null, $userId);
    }

    /**
     * Manda allo studente la mail di avvenuta iscrizione ad un corso di Moodle, 
     * con il link per accedere al corso.
     *
     * @param type $course
     * @param type $userId
     * @param type $paypal
     * @param type $courseCost
     * @param type $enrollerId Chi iscrive chi
     * @return type
     */
    public static function sendEmailEnrolledInCourse($course, $userId, $paypal = false, $courseCost = null, $enrollerId = null) {
        $from = \Yii::$app->params['supportEmail'];

        $userStudent = User::findOne([
            'id' => $userId,
        ]);

        $to = $userStudent->email;
        
        $courseUrl = '';
        if (!empty($course->community_id)) {
            $urlParams = [
                '/community/join',
                'id' => $course->community_id,
            ];
        }

        if (!empty($urlParams)) {
            $courseUrl = Yii::$app->urlManager->createAbsoluteUrl($urlParams);
        }

        $subject = AmosMoodle::t("amosmoodle", '#welcome_to_the_course_email_subject', [
            'name' => $course->name
        ]);

        if ($paypal == true) {
            $body [] = AmosMoodle::t('amosmoodle', '#confirm_paypal_payment', [
                'cost' => $courseCost,
                'name' => $course->name
            ]);
        }

        $body [] = AmosMoodle::t('amosmoodle', '#subscribed', ['name' => $course->name]);
        $body [] = AmosMoodle::t('amosmoodle', '#course_info_link', [
            'courseUrl' => $courseUrl
        ]);

        $params = [
            'userProfile' => $userStudent->userProfile->toArray(),
            'body' => $body,
        ];

        $layout = '@vendor/open20/amos-moodle/src/mail/generic/generic-user-html';

        // Send an email to enroller user
        if ($enrollerId != null) {
            
            $userEnroller = User::findOne(['id' => $enrollerId]);
            $to = $userEnroller->email;
            return self::sendEmail($from, $to, $subject, $params, $layout, null, $userId);
        }

        return self::sendEmail($from, $to, $subject, $params, $layout, null, $userId);
    }

    /**
     * 
     * @param type $from
     * @param type $to
     * @param type $subject
     * @param type $params
     * @param type $layout
     * @param type $layoutHtml
     * @param type $userId
     * @return type
     */
    public static function sendEmail($from, $to, $subject, $params, $layout, $layoutHtml = null, $userId = null) {
        $email = new Email();
        $text = $email->renderMailPartial($layout, $params, $userId);
      
        return $email->sendMail($from, $to, $subject, $text);
    }

}
