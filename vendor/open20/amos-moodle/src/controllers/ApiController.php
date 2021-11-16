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
 
 
class ApiController extends \yii\rest\Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            /** 
             * Performs authorization by token
             */
            'tokenAuth' => [
                'class' => \conquer\oauth2\TokenAuth::className(),
            ],
        ];
    }

    /**
     * Returns username and email
     */
    public function actionIndex()
    {
        $user = \Yii::$app->user->identity;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'username' => $user->username,
            'email' =>  $user->email,
        ];
    }
}