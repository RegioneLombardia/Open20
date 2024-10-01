<?php
namespace open20\amos\videoconference\controllers;
//namespace console\controllers;


use open20\amos\admin\models\UserProfile;
use open20\amos\videoconference\AmosVideoconference;
use open20\amos\videoconference\models\Videoconf;
use open20\amos\videoconference\models\VideoconfUsersMm;
use open20\amos\videoconference\utils\EmailUtil;
use yii\console\Controller;
use yii\helpers\Console;
use Yii;

class CronController extends Controller
{

    public function actionStart_video_conference(){
        $programmedConferences = Videoconf::find()->andWhere(['status' => Videoconf::STATUS_FUTURE])->all();
        $now = new \DateTime();
        Console::stdout("*** CRON START VIDEO CONFERENCE ***" . "\n");
        $count = 0;
        /** @var  $videoConference Videoconf */
        foreach ($programmedConferences as $videoConference) {
            if($videoConference->isVideoConferenceActivable() && empty($videoConference->id_room_videoconference)) {
                $videoConference->setIdRoomVideoconference();
                $videoConference->status = Videoconf::STATUS_RUNNING;
                if($videoConference->save(false)) {
                    Console::stdout("videoconferences started: ID:" . print_r($videoConference->id, true) . " title: " . print_r($videoConference->title, true) . "\n");
                    $count++;
                }
            } else if(new \DateTime($videoConference->end_date_hour) < $now) {
                Console::stdout("videoconferences auto-closed: ID:" . print_r($videoConference->id, true) . " title: " . print_r($videoConference->title, true) . "\n");
                $videoConference->status = Videoconf::STATUS_END;
                $videoConference->save(false);
            }
        }

        Console::stdout("n. videoconferences started: " . print_r($count,true) . "\n");
        Console::stdout("*** END CRON ***" . "\n");
    }

    public function actionEnd_video_conference(){
        $runningConferences = Videoconf::find()->andWhere(['status' => Videoconf::STATUS_RUNNING])->all();

        Console::stdout("*** CRON END VIDEO CONFERENCE ***" . "\n");
        $count = 0;
        /** @var  $videoConference Videoconf */
        foreach ($runningConferences as $videoConference) {
            if(!$videoConference->isVideoConferenceRoomActive()) {
                $videoConference->id_room_videoconference = null;
                $videoConference->status = Videoconf::STATUS_END;
                if($videoConference->save(false)) {
                    Console::stdout("videoconferences ended: ID:" . print_r($videoConference->id, true) . " title: " . print_r($videoConference->title, true) . "\n");
                    $count++;
                }
            }
        }

        Console::stdout("n. videoconferences ended: " . print_r($count,true) . "\n");
        Console::stdout("*** END CRON ***" . "\n");
    }

    /**
     * @return void
     */
    public function actionStart_end_video_conference() {
        $this->actionStart_video_conference();
        $this->actionEnd_video_conference();
    }

    /**
     *
     */
    public function actionSend_email_reminder()
    {
        $module = \Yii::$app->getModule(AmosVideoconference::getModuleName());
        // find scheduled videoconferences which haven't been sent a reminder
        $programmedConferences = Videoconf::find()
            ->andWhere(['status' => Videoconf::STATUS_FUTURE])
            ->andWhere(['reminder_sent' => 0])->all();
        $now = new \DateTime();

        Console::stdout("*** CRON SEND EMAIL REMINDER VIDEOCONFERENCE ***" . "\n");
        $count = 0;
        /** @var  $videoconference Videoconf */
        foreach ($programmedConferences as $videoconference) {
            $notification_before_conference = !empty($videoconference->notification_before_conference) ? $videoconference->notification_before_conference : $module->minuteReminder;
            $intervalReminder =  new \DateInterval('PT' . trim($notification_before_conference) . 'M');
            $dateHourReminder = new \DateTime($videoconference->begin_date_hour);
            $dateHourReminder->sub($intervalReminder);

            if($videoconference->status == Videoconf::STATUS_FUTURE) {
                if($now >= $dateHourReminder) {
                    $collegati = $videoconference->getVideoconfUsersMms()->all();
                    /** @var  $u VideoconfUsersMm */
                    foreach ((array)$collegati as $u) {
                        $profile = UserProfile::findOne([$u->user_profile_id]);
                        $sent = EmailUtil::sendNotification($videoconference, $profile->user);
                        if($sent) {
                            Console::stdout( print_r($videoconference->id, true) . " - reminder sent to: user_profile_id:" . print_r($profile->id, true) . " email: " . print_r($profile->user->email, true) . "\n");
                            $count++;
                        }
                    }
                    $videoconference->reminder_sent = 1;
                    $videoconference->save(false);
                }
            }
        }

        Console::stdout("n. Email sent: " . print_r($count,true) . "\n");
        Console::stdout("*** END CRON ***" . "\n");
    }



}