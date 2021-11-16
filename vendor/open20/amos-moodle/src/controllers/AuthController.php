<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\moodle\controllers
 * @category   CategoryName
 */

namespace open20\amos\moodle\controllers;

use yii\web\Controller as YiiController;
use Yii;
 
class AuthController extends YiiController
{
    
    public function init() {    
        //$this->setModelObj(new ServiceCall());
        parent::init();
    }
    
    public function behaviors()
    {
        return [ 
            /** 
             * checks oauth2 credentions
             * and performs OAuth2 authorization, if user is logged on
             */
            'oauth2Auth' => [
                'class' => \conquer\oauth2\AuthorizeFilter::className(),
                'only' => ['index'],
            ],            
        ];
    }
    public function actions()
    {
        return [
            // returns access token
            'token' => [
                'class' => \conquer\oauth2\TokenAction::classname(),
            ],
        ];
    }
    /**
     * Display login form to authorize user
     */
    public function actionIndex()
    {
        if (Yii::$app->getUser()->isGuest){
            return Yii::$app->getUser()->loginRequired();
        } else {
            return '';
        }
    }
}