<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\controllers 
 */
 
namespace open20\amos\events\controllers;
use open20\amos\events\models\base\EventConfigurations;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;


/**
 * Class EventConfigurationsController 
 * This is the class for controller "EventConfigurationsController".
 * @package open20\amos\events\controllers 
 */
class EventConfigurationsController extends \open20\amos\events\controllers\base\EventConfigurationsController
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
                            'configure',
                        ],
                        'roles' => ['CONFIGURATOR_EVENTS']
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
     * @return mixed|\yii\web\Response
     */
    public function actionConfigure(){
        $config = EventConfigurations::findOne(1);
        if($config) {
            return $this->actionUpdate(1);
        }
        return $this->redirect('index');
    }


}
