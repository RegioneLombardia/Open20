<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\controllers 
 */
 
namespace open20\amos\events\controllers;
use open20\amos\events\models\Event;
use open20\amos\events\models\EventContainer;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * Class EventContainerController 
 * This is the class for controller "EventContainerController".
 * @package open20\amos\events\controllers 
 */
class EventContainerController extends \open20\amos\events\controllers\base\EventContainerController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [
                                'order',
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
        return $behaviors;
    }

    /**
     * @param $id
     * @param $slider_id
     * @param $direction
     * @return \yii\web\Response
     */
    public function actionOrder($id, $event_container_id, $direction, $urlRedirect = null)
    {
        $ok = $this->order($id, $event_container_id, $direction);
        if (!$ok) {
            \Yii::$app->session->addFlash('danger', \Yii::t('amossitemanager', 'Error while reordering'));
        }
        if ($urlRedirect) {
            return $this->redirect(urldecode($urlRedirect));
        } else {
            return $this->redirect(['/events/event-container/update', 'id' => $event_container_id]);
        }
    }

    /**
     * @param $id
     * @param $event_container_id
     * @param string $direction
     * @return bool
     */
    protected function order($id, $event_container_id, $direction = 'up')
    {
        $container = EventContainer::findOne($event_container_id);
        if ($container) {
            $elements = $container->getEvents()->orderBy('event_container_order')->all();

            $orderList = [];
            foreach ($elements as $element) {
                $orderList [] = $element->id;
            }

            //find the element  in the ids array and move it up or down
            $indexElemToMove = array_search($id, $orderList);
            if ($direction == 'up') {
                $orderList = $this->up($orderList, $indexElemToMove);
            } else {
                $orderList = $this->down($orderList, $indexElemToMove);
            }

            //save the element with the new order
            $this->resetNumberOrder($orderList);
            return true;
        }
        return false;
    }

    /**
     * Move the element in the x position up
     * @param $array
     * @param $x
     * @return array
     */
    public function up($array, $x)
    {
        if ($x > 0 and $x < count($array)) {
            $b   = array_slice($array, 0, ($x - 1), true);
            $b[] = $array[$x];
            $b[] = $array[$x - 1];
            $b   += array_slice($array, ($x + 1), count($array), true);
            return($b);
        } else {
            return $array;
        }
    }

    /** Move the element in the x position down
     * @param $array
     * @param $x
     * @return array
     */
    public function down($array, $x)
    {
        if (count($array) - 1 > $x) {
            $b   = array_slice($array, 0, $x, true);
            $b[] = $array[$x + 1];
            $b[] = $array[$x];
            $b   += array_slice($array, $x + 2, count($array), true);
            return($b);
        } else {
            return $array;
        }
    }

    /**
     * @param $orderList
     */
    public function resetNumberOrder($orderList)
    {
        $i = 1;
        foreach ($orderList as $id) {
            $event        = Event::findOne($id);
            $event->event_container_order = $i;
            $event->save(false);
            $i++;
        }
    }
}
