<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\chrono\controllers
 */

namespace amos\chrono\controllers;

use amos\chrono\controllers\base\ChronoTaskController as BaseChronoTaskController;
use amos\chrono\Module;
use open20\amos\core\user\User;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * Class ChronoTaskController
 * This is the class for controller "ChronoTaskController".
 * @package amos\chrono\controllers
 */
class ChronoTaskController extends BaseChronoTaskController
{

    /**
     * 
     */
    public function init()
    {
        parent::init();
        Yii::$app->params['bsVersion']                     = '4.x';
        \Yii::$app->view->params['customClassMainContent'] = 'box-container sidebar-setting';
        $this->setUpLayout('bootstrap-italia-layout-with-sidebar');
        \Yii::$app->getView()->params['bi-menu-sidebar']   = static::getSidebar();
    }

    /**
     *
     * @return array
     */
    protected static function getSidebar()
    {
        $menu = [
            [
                'mainMenu' => [
                    'label' => Module::t('amoschrono', 'Dashboard'),
                    'icon' => '/sprite/material-sprite.svg#ic_dashboard',
                    'activeTargetAction' => 'index',
                    'titleLink' => Module::t('amoschrono', 'Chrono Tasks'),
                    'url' => '/chrono/chrono-task/index'
                ],
            ],
            [
                'mainMenu' => [
                    'label' => Module::t('amoschrono', '#new_task'),
                    'icon' => '/sprite/material-sprite.svg#ic_input',
                    'activeTargetAction' => 'create',
                    'titleLink' => Module::t('amoschrono', 'Crea'),
                    'url' => '/chrono/chrono-task/create'
                ],
            ],
        ];

        return $menu;
    }

    /**
     *
     * @return type
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(parent::behaviors(),
                [
                    'access' => [
                        'class' => AccessControl::className(),
                        'rules' => [
                            [
                                'actions' => ['optout', 'disable-user'],
                                'allow' => true,
                                'roles' => ['?', '@']
                            ],
                        ],
                    ],
                    'verbs' => [
                        'class' => VerbFilter::className(),
                        'actions' => [
                            'request-information' => ['post', 'get']
                        ]
                    ]
        ]);

        return $behaviors;
    }

    /**
     * 
     * @param string $token
     */
    public function actionOptout($token)
    {
        Yii::$app->params['bsVersion']                     = '4.x';
        \Yii::$app->view->params['customClassMainContent'] = 'box-container sidebar-setting';
        $this->setUpLayout('bootstrap-italia-layout-no-sidebar');
        $appName                                           = \Yii::$app->name;
        $user                                              = User::find()->andWhere(new Expression("MD5(CONCAT(user.id, '".$appName."', user.username)) = '".$token."'"))->one();
        if (empty($user)) {
            return $this->render('security-message',
                    [
                        'title_message' => Module::t('amoschrono', 'Errore'),
                        'result_message' => Module::t('amoschrono',
                            '#invalid_token')
            ]);
        }

        return $this->render('disable_user',
                [
                    'model' => $user,
                    'token' => $token
        ]);
    }

    /**
     *
     * @param type $token
     * @return type
     */
    public function actionDisableUser($token)
    {
        Yii::$app->params['bsVersion']                     = '4.x';
        \Yii::$app->view->params['customClassMainContent'] = 'box-container sidebar-setting';
        $this->setUpLayout('bootstrap-italia-layout-no-sidebar');
        $appName                                           = \Yii::$app->name;
        $user                                              = User::find()->andWhere(new Expression("MD5(CONCAT(user.id, '".$appName."', user.username)) = '".$token."'"))->one();
        if (empty($user)) {
            return $this->render('security-message',
                    [
                        'title_message' => Module::t('amoschrono', 'Errore'),
                        'result_message' => Module::t('amoschrono',
                            '#invalid_token')
            ]);
        } else {
            $userProfile         = $user->userProfile;
            $userProfile->attivo = 0;
            $user->status        = 0;
            $user->save(false);
            $userProfile->save(false);
        }
        $this->redirect(Yii::$app->params['platform'] ['backendUrl']);
    }
}