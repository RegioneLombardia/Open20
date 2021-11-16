<?php

namespace open20\amos\translation\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use open20\amos\dashboard\controllers\base\DashboardController;

/**
 * Translation controller
 */
class TranslateController extends DashboardController {

    public $defaultAction = 'index';
    
    /**
     * @var string $layout Layout for the dashboard
     */
    public $layout = 'main';

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['language', 'error', 'contents', 'records', 'update', 'index', 'translators', 'user-language'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function init() {

        parent::init();
        $this->setUpLayout();
        // custom initialization code goes here
    }

    public function actionIndex()
    {
        $this->setUpLayout('dashboard_interna');
        Url::remember();
       
        return $this->render('index');
    }


    /**
     * @param null $layout
     * @return bool
     */
    public function setUpLayout($layout = null)
    {
        if ($layout === false) {
            $this->layout = false;
            return true;
        }
        $this->layout = (!empty($layout)) ? $layout : $this->layout;
        $module = \Yii::$app->getModule('layout');
        if (empty($module)) {
            if (strpos($this->layout, '@') === false) {
                $this->layout = '@vendor/open20/amos-core/views/layouts/'.(!empty($layout) ? $layout : $this->layout);
            }
            return true;
        }
        return true;
    }
}
