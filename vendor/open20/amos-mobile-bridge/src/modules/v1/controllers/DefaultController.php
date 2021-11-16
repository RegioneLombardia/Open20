<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\mobile\bridge
 * @category   CategoryName
 */

namespace open20\amos\mobile\bridge\modules\v1\controllers;

use yii\base\Event;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;

/**
 * Class FileController
 * @package open20\amos\mibile\bridge\controllers
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviours = parent::behaviors();
        unset($behaviours['authenticator']);

        return ArrayHelper::merge($behaviours, [
            'authenticator' => [
                'class' => CompositeAuth::className(),
                'authMethods' => [
                    'bearerAuth' => [
                        'class' => HttpBearerAuth::className(),
                    ]
                ],
            ]
        ]);
    }

    public function init()
    {
        parent::init();

        //Drop CSRF cause is API
        $this->enableCsrfValidation = false;

        //Drop Events on login
        Event::off(\yii\web\User::className(), \yii\web\User::EVENT_AFTER_LOGIN);
    }
}
