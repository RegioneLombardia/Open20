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


use open20\amos\admin\models\UserProfile;
use open20\amos\core\user\User;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\filters\RateLimiter;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

class UserController extends DefaultController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviours = parent::behaviors();

        return ArrayHelper::merge($behaviours, [
            'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                    'application/xml' => Response::FORMAT_XML,
                ],
            ],
            'verbFilter' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'update' => ['post'],
                    'image' => ['post'],
                    'follow' => ['post'],
                    'unfollow' => ['post'],
                ],
            ],
            /*
            'rateLimiter' => [
                'class' => RateLimiter::className(),
            ],
            */
        ]);
    }

    public function actionFollow()
    {
        return true;
    }

    public function actionUnfollow()
    {
        return true;
    }

    public function actionUpdate()
    {
        $user = User::findOne(['id' => \Yii::$app->user->id]);
        $profile = $user->userProfile;

        if (!$user->load(\Yii::$app->request->post())) {
            throw new ForbiddenHttpException($user->getErrorSummary()[0]);
        }

        if (!$user->validate()) {
            throw new ForbiddenHttpException($user->getFirstError());
        }

        if (!$profile->load(\Yii::$app->request->post())) {
            throw new ForbiddenHttpException($profile->getErrorSummary()[0]);
        }

        if (!$profile->validate()) {
            throw new ForbiddenHttpException($profile->getErrorSummary()[0]);
        }

        $postProfile = \Yii::$app->request->post('UserProfile');
        $postUser = \Yii::$app->request->post('User');

        if(isset($postProfile['id']) || isset($postProfile['user_id']) || isset($postUser['id'])) {
            throw new ForbiddenHttpException('Permesso Negato');
        }

        if (!$user->save(false)) {
            throw new ForbiddenHttpException($profile->getErrorSummary()[0]);
        }

        if (!$profile->save(false)) {
            throw new ForbiddenHttpException($profile->getErrorSummary()[0]);
        }

        return true;
    }

    public function actionImage()
    {
        return true;
    }

}