<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\controllers 
 */
 
namespace open20\amos\events\controllers;
use open20\amos\core\helpers\Html;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventLocation;
use open20\amos\events\models\EventLocationEntrances;
use yii\bootstrap4\ActiveForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Response;

/**
 * Class EventLocationController 
 * This is the class for controller "EventLocationController".
 * @package open20\amos\events\controllers 
 */
class EventLocationController extends \open20\amos\events\controllers\base\EventLocationController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'get-entrances',
                        ],
                        'roles' => ['EVENTS_ADMINISTRATOR', 'EVENTS_CREATOR']
                    ]
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
     * @return array
     */
    public function actionGetEntrances($id, $eventId = null){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $this->model = Event::findOne($eventId);
        if(empty($eventId)){
            $this->model = new Event();
        }
        if($id == 'other'){
            $form = new ActiveForm();
            $eventLocation = new EventLocation();
            $eventLocationEntrance = new EventLocationEntrances();
            $html =  $this->renderAjax('@vendor/open20/amos-events/src/views/event-dashboard/_parts/_personalized_location',
                [
                    'eventLocation' => $eventLocation,
                    'eventLocationEntrance' => $eventLocationEntrance,
                    'form' => $form
                ]);
            return ['html' => $html];

        }
        $location = EventLocation::findOne($id);
        $data = [];
        if($location){
            $entrancees = $location->eventLocationEntrances;
            foreach ($entrancees as $entrance){
                $data [$entrance->id] = $entrance->name;
            }
        }
        $html = Html::activeRadioList($this->model,'event_location_entrance_id', $data );

        return ['html' => $html];
    }

}
