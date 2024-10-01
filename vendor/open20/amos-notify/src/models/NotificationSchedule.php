<?php

namespace open20\amos\notificationmanager\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "notification_schedule".
 */
class NotificationSchedule extends \open20\amos\notificationmanager\models\base\NotificationSchedule
{
    const STATUS_WORKING = 'working';
    const STATUS_DONE = 'done';
    const STATUS_ERROR = 'error';

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


    public function getEditFields()
    {
        $labels = $this->attributeLabels();

        return [
            [
                'slug' => 'status',
                'label' => $labels['status'],
                'type' => 'string'
            ],
            [
                'slug' => 'type',
                'label' => $labels['type'],
                'type' => 'integer'
            ],
            [
                'slug' => 'last_notified_user_id',
                'label' => $labels['last_notified_user_id'],
                'type' => 'integer'
            ],
            [
                'slug' => 'ended_at',
                'label' => $labels['ended_at'],
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
     * @param $arrayNotifications
     * @param $arrayNotificationComments
     * @return NotificationSchedule
     */
    public static function createSchedule($arrayNotifications, $arrayNotificationComments, $type, $maxUserIdToSend, $segmentationInfos)
    {

        $schedule = new NotificationSchedule();
        $schedule->type = $type;
        $schedule->max_user_id_to_notify = $maxUserIdToSend;
        if(empty($maxUserIdToSend)){
            $schedule->status = NotificationSchedule::STATUS_DONE;
        }else{
            $schedule->status = NotificationSchedule::STATUS_WORKING;
        }
        if(isset($segmentationInfos['offset'])){
            $schedule->start_user_id =  $segmentationInfos['offset'];
        }
        $schedule->save(false);
        foreach ($arrayNotifications as $notification) {
            $notifyContent = new NotificationScheduleContent();
            $notifyContent->notification_schedule_id = $schedule->id;
            $notifyContent->notification_id = $notification['id'];
            $notifyContent->classname = $notification['class_name'];
            $notifyContent->save(false);
        }
        foreach ($arrayNotificationComments as $notificationComment) {
            $notifyContent = new NotificationScheduleContent();
            $notifyContent->notification_schedule_id = $schedule->id;
            $notifyContent->notification_id = $notificationComment['id'];
            $notifyContent->classname = $notificationComment['class_name'];
            $notifyContent->save(false);
        }
        return $schedule;
    }

    /**
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public static function notificationScheduled()
    {
        $notificationSchedule = NotificationSchedule::find()->andWhere(['status' => NotificationSchedule::STATUS_WORKING])->one();
        return $notificationSchedule;
    }

    /**
     * @param $user_id
     * @return bool
     */
    public function isLastUserToSend($user_id){
            return $this->max_user_id_to_notify == $user_id;
    }
}
