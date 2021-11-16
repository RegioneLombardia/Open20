<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\controllers
 */

namespace open20\amos\events\controllers;
use open20\amos\events\AmosEvents;
use open20\amos\events\models\Event;
use open20\amos\events\models\search\EventCalendarsSlotsSearch;
use open20\amos\events\utility\EventMailUtility;
use open20\amos\events\utility\EventsUtility;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;

/**
 * Class EventCalendarsSlotsController
 * This is the class for controller "EventCalendarsSlotsController".
 * @package open20\amos\events\controllers
 */
class EventCalendarsSlotsController extends \open20\amos\events\controllers\base\EventCalendarsSlotsController
{

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'book-slot',
                            'unbook-slot',
                            'my-booking'
                        ],
                        'roles' => ['@']
                    ],


                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get']
                ]
            ]
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws ForbiddenHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionBookSlot($id, $url = null, $affiliation = null, $cellphone = null)
    {
        $this->model = $this->findModel($id);
        $calendar = $this->model->eventCalendars;
        $isParticipant = EventsUtility::isEventParticipant($this->model->eventCalendars->event->id, \Yii::$app->user->id);
        if(!$isParticipant){
            throw new ForbiddenHttpException();
        }
        if (empty($this->model->user_id)) {
            $this->model->user_id = \Yii::$app->user->id;
            $this->model->booked_at = date('Y-m-d H:i:s');
            $this->model->affiliation = $affiliation;
            $this->model->cellphone = $cellphone;
            $this->model->save(false);
            EventMailUtility::sendEmailSlotBooked($this->model);
            EventMailUtility::sendEmailPartnerSlotBooked($this->model);

            \Yii::$app->session->addFlash('success', AmosEvents::t('amosevents', 'Hai prenotato correttamente questo slot'));

        } else {
            \Yii::$app->session->addFlash('danger', AmosEvents::t('amosevents', 'Questo slot è già prenotato'));
        }
        return $this->redirect(['/events/event-calendars/view', 'id' => $calendar->id,'url' => $url]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws ForbiddenHttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUnbookSlot($id, $url = null)
    {
        $this->model = $this->findModel($id);
        $calendar = $this->model->eventCalendars;
        if(\Yii::$app->user->id == $this->model->user_id) {
            if (!empty($this->model->user_id)) {
                EventMailUtility::sendEmailSlotUnbooked($this->model);
                EventMailUtility::sendEmailPartnerSlotUnbooked($this->model);
                $this->model->user_id = null;
                $this->model->booked_at = null;
                $this->model->affiliation = null;
                $this->model->cellphone = null;
                $this->model->save(false);

                \Yii::$app->session->addFlash('success', AmosEvents::t('amosevents', 'Hai annullato correttamente la prenotazione a questo questo slot'));
            } else {
                \Yii::$app->session->addFlash('danger', AmosEvents::t('amosevents', 'Questo slot è già vuoto'));
            }
        } else {
            throw new ForbiddenHttpException();
        }
        return $this->redirect(['/events/event-calendars/view', 'id' => $calendar->id, 'url' => $url]);
    }

    /**
     * @param $eventId
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionMyBooking($eventId){
        $this->setUpLayout('list');
        $event = Event::findOne($eventId);
        $modelSearch = new EventCalendarsSlotsSearch();
        $modelSearch->event = $eventId;
        $dataProvider = $modelSearch->mySlotsAllSearch([]);

        return $this->render('my-booking', [
            'event'=> $event,
            'dataProvider' => $dataProvider,
        ]);
    }

}
