<?php

namespace amos\sitemanagement\controllers;
use amos\sitemanagement\models\base\SiteManagementContainerElementMm;
use amos\sitemanagement\models\SiteManagementContainer;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
* This is the class for controller "SiteManagementContainerElementMmController".
*/
class SiteManagementContainerElementMmController extends base\SiteManagementContainerElementMmController
{

    /**
     * @inheritdoc
     */
    public function behaviors() {
        $behaviors = ArrayHelper::merge(parent::behaviors(), [
                    'access' => [
                        'class' => AccessControl::className(),
                        'rules' => [
                            [
                                'allow' => true,
                                'actions' => [
                                    'order-container',
                                ],
                                'roles' => ['SITE_MANAGEMENT_ADMINISTRATOR',
                                    'SITE_MANAGEMENT_EDITOR']
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
    public function actionOrderContainer($id, $container_id, $direction){
        $container = SiteManagementContainer::findOne($container_id);
        if($container) {
            $elements = $container->getSiteManagementContainerElementMms()->orderBy('elem_order ASC')->all();
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

        }
        else {
            \Yii::$app->session->addFlash('danger', \Yii::t('amossitemanager', 'Error while reordering'));
        }
        return $this->redirect(['/sitemanagement/site-management-container/update', 'id' => $container->id]);



    }

    /**
     * Move the element in the x position up
     * @param $array
     * @param $x
     * @return array
     */
    public function up($array,$x) {
        if( $x > 0 and $x < count($array) ) {
            $b = array_slice($array,0,($x-1),true);
            $b[] = $array[$x];
            $b[] = $array[$x-1];
            $b += array_slice($array,($x+1),count($array),true);
            return($b);
        } else { return $array; }
    }

    /** Move the element in the x position down
     * @param $array
     * @param $x
     * @return array
     */
    public function down($array,$x) {
        if( count($array)-1 > $x ) {
            $b = array_slice($array,0,$x,true);
            $b[] = $array[$x+1];
            $b[] = $array[$x];
            $b += array_slice($array,$x+2,count($array),true);
            return($b);
        } else { return $array; }
    }

    /**
     * @param $orderList
     */
    public function resetNumberOrder($orderList){
        $i = 1;
        foreach ($orderList as $id) {
            $containerElemMm = SiteManagementContainerElementMm::findOne($id);
            $containerElemMm->elem_order = $i;
            $containerElemMm->save(false);
            $i++;
        }
    }



}
