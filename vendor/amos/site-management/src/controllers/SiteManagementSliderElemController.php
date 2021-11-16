<?php

namespace amos\sitemanagement\controllers;

use amos\sitemanagement\models\SiteManagementSlider;
use amos\sitemanagement\models\SiteManagementSliderElem;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * This is the class for controller "SiteManagementSliderElemController".
 */
class SiteManagementSliderElemController extends base\SiteManagementSliderElemController
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
                                'order-slider',
                            ],
                            'roles' => ['SITEMANAGEMENTSLIDERELEM_UPDATE']
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
    public function actionOrderSlider($id, $slider_id, $direction, $urlRedirect = null)
    {
        $ok = $this->orderSlider($id, $slider_id, $direction);
        if (!$ok) {
            \Yii::$app->session->addFlash('danger', \Yii::t('amossitemanager', 'Error while reordering'));
        }
        if ($urlRedirect) {
            return $this->redirect(urldecode($urlRedirect));
        } else {
            return $this->redirect(['/sitemanagement/site-management-slider/update', 'id' => $slider_id]);
        }
    }

    /**
     * 
     * @param integer $id
     * @param integer $slider_id
     * @param string $direction 'up' or 'down'
     * @return boolean
     */
    protected function orderSlider($id, $slider_id, $direction = 'up')
    {
        $slider = SiteManagementSlider::findOne($slider_id);
        if ($slider) {
            $elements = $slider->getSliderElems()->orderBy('order')->all();

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
            $sliderElem        = SiteManagementSliderElem::findOne($id);
            $sliderElem->order = $i;
            $sliderElem->save(false);
            $i++;
        }
    }
}