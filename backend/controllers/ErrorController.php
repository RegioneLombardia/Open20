<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */
/**
 */

namespace backend\controllers;

use common\models\FirstAccessForm;
use common\models\ForgotPasswordForm;
use common\models\LoginForm;
use common\models\User;
use open20\amos\admin\models\UserProfile;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use \yii\helpers\Url;
use yii\web\Controller;
use yii\web\Cookie;

class ErrorController extends Controller
{


    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'error',
                            'error404',
                            'error403',
                            'error500',
                        ],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'logout' => ['post'],
                ],
            ],
        ];
    }

  public function actions() {
        $this->layout = '@vendor/open20/amos-layout/src/views/layouts/main';
        return [
            'error' => [
                'class' => 'backend\actions\ErrorAction',
            ],
        ];
    }
}