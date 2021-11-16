<?php

/**
 * This is the class for controller "AdministrationController".
 */
namespace open20\amos\moodle\controllers;
use open20\amos\moodle\AmosMoodle;

use yii\web\Controller as YiiController;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

class AdministrationController extends YiiController {
     public $layout = '@vendor/open20/amos-core/views/layouts/main';
     
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                        ],
                        'roles' => [AmosMoodle::MOODLE_ADMIN]
                    ],
                ]
            ],
        ]);

        return $behaviors;
    }
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
    
    /**
     * @inheritdoc
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
}