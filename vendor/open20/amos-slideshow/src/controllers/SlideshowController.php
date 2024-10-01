<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\slideshow\controllers
 * @category   CategoryName
 */

namespace open20\amos\slideshow\controllers;

use open20\amos\slideshow\AmosSlideshow;
use open20\amos\slideshow\models\SlideshowRoute;
use open20\amos\slideshow\models\SlideshowUserflag;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Class SlideshowController
 * @package open20\amos\slideshow\controllers
 *
 * This is the class for controller "SlideshowController".
 */
class SlideshowController extends \open20\amos\slideshow\controllers\base\SlideshowController
{
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
                            'cambia',
                            'route-by-role',
                        ],
                        'roles' => ['@']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'route-by-role' => ['post']
                ]
            ]
        ]);
        return $behaviors;
    }

    public function beforeAction($action) {
        $urlLinkAll = null;
        
        if (!parent::beforeAction($action)) {
            return false;
        }

        $this->view->params = [
            'isGuest' => Yii::$app->user->isGuest,
            'modelLabel' => 'Slideshow',
            'titleSection' => AmosSlideshow::t('amosslideshow', 'Tutti gli slideshow'),
            'subTitleSection' => null,
            'urlLinkAll' => $urlLinkAll,
            'labelLinkAll' => AmosSlideshow::t('amosslideshow', 'Tutte le slides'),
            'titleLinkAll' => $titleLinkAll,
            'labelCreate' => AmosSlideshow::t('amosslideshow', 'Nuova'),
            'titleCreate' => AmosSlideshow::t('amosslideshow', 'Aggiungi slideshow'),
            'labelManage' => AmosSlideshow::t('amosslideshow', 'Gestisci'),
            'titleManage' => AmosSlideshow::t('amosslideshow', 'Gestisci slideshow'),
            'urlCreate' => '/slideshow/slideshow/create',
            'urlManage' => '#',
            //'hideCreate' => true,
        ];

        // other custom code here
        return true;
    }
    
    /**
     * @return bool
     */
    public function actionCambia()
    {
        if (Yii::$app->request->isAjax) {
            if (!Yii::$app->getUser()->isGuest) {
                $data = Yii::$app->request->post();
                if (isset($data['set']) && $data['set'] == 'true') {
                    $routeId = $data['value'];
                    $userId = Yii::$app->getUser()->getId();
                    $slideshowUserflag = SlideshowUserflag::findOne(['user_id' => $userId, 'slideshow_route_id' => $routeId]);
                    if (!$slideshowUserflag) {
                        $slideshowUserflag = new SlideshowUserflag();
                        $slideshowUserflag->slideshow_route_id = $routeId;
                        $slideshowUserflag->user_id = $userId;
                        $slideshowUserflag->save(FALSE);
                    }
                } else {
                    $routeId = $data['value'];
                    $userId = Yii::$app->getUser()->getId();
                    $slideshowUserflag = SlideshowUserflag::deleteAll(['user_id' => $userId, 'slideshow_route_id' => $routeId]);
                }
            }
        }

        return TRUE;
    }

    public function actionRouteByRole()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
            $id_selected = end($_POST['depdrop_params']);
            $slideshow = new SlideshowRoute();
            $routes = $slideshow->getRotte(NULL, $id);
            $selected = null;

            if ($id != null && count($routes) > 0) {
                $selected = '';
                foreach ($routes as $i => $route) {

                    $out[] = ['id' => $i, 'name' => $route];

                    if ($id_selected) {
                        $selected = $id_selected;
                    } elseif ($i == 0) {
                        $selected = $i;
                    }
                }
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected' => $selected]);
                return;
            }
        }

        echo Json::encode(['output' => '', 'selected' => '']);
    }
}
