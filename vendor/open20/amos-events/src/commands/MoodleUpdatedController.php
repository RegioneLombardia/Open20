<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 29/04/2020
 * Time: 11:52
 */

namespace open20\amos\events\commands;


use open20\amos\events\AmosEvents;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventGroupReferentMm;
use open20\amos\events\models\EventInternalList;
use open20\amos\events\models\EventInvitationSent;
use open20\amos\events\utility\EventMailUtility;
use open20\amos\events\utility\EventsUtility;
use yii\base\InvalidConfigException;
use yii\console\Controller;
use yii\helpers\Console;
use yii\helpers\Url;

class MoodleUpdatedController extends Controller
{

    public $moduleMoodle = null;
    public $enabled = false;
    public $courseid;
    public $role;


    /**
     *
     */
    public function init()
    {
        parent::init();

        $moduleMoodle = \Yii::$app->getModule('moodle');
        $moduleEvents = \Yii::$app->getModule('events');
        if($moduleMoodle && $moduleEvents && !empty($moduleEvents->operatorCandidate['enabled'])){
            $this->enabled = $moduleEvents->operatorCandidate['enabled'];
            $this->courseid = $moduleEvents->operatorCandidate['courseid'];
            $this->role = $moduleEvents->operatorCandidate['role'];
        }else {
           return false;
        }
        return true;
    }

    /**
     * @throws InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionSendEmailCoursesCompleted()
    {
        Console::stdout('###################################################' .PHP_EOL);
        Console::stdout('##### START CRON send-email-courses-completed #####' .PHP_EOL);
        Console::stdout('###################################################' .PHP_EOL);

        $members = EventGroupReferentMm::find()
            ->andWhere(['IS', 'status', null])
            ->andWhere([ 'exclude_from_query' => true])->all();

       /** @var  $member EventGroupReferentMm*/
        foreach ($members as $member){
            Console::stdout('- PROCESSING user '.$member->user_id .PHP_EOL);

            $isCompleted = EventsUtility::checkIfCourseIsCompleted($this->courseid, $member->user_id);
            if($isCompleted){
                Console::stdout('- COURSE COMPLETED '.$member->user_id .PHP_EOL);

                $member->status = EventGroupReferentMm::STATUS_REQUEST_ACTIVATION;
                if($member->save(false)){
                    EventMailUtility::sendEmailEnableOperator($member, $member->user_id);
                }
            }
        }
    }

}