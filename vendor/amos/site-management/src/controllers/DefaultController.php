<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\controllers
 * @category   CategoryName
 */

namespace amos\sitemanagement\controllers;

use open20\amos\dashboard\controllers\base\DashboardController;
use yii\helpers\Url;

/**
 * Class DefaultController
 * @package amos\sitemanagement\controllers
 */
class DefaultController extends DashboardController
{
    /**
     * @var string $layout Layout for internal dashboard.
     */
    public $layout = 'dashboard_interna';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setUpLayout();
    }

    /**
     * @param string|null $layout
     * @return bool
     */
    public function setUpLayout($layout = null)
    {
        if ($layout === false) {
            $this->layout = false;
            return true;
        }
        $module = \Yii::$app->getModule('layout');
        if (empty($module)) {
            $this->layout = '@vendor/open20/amos-core/views/layouts/' . (!empty($layout) ? $layout : $this->layout);
            return true;
        }
        $this->layout = (!empty($layout)) ? $layout : $this->layout;
        return true;
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionIndex($oldDashboard = null)
    {
//        if (is_null($oldDashboard)) {
//            return $this->redirect(['/sitemanagement/page-content/index']);
//        }
        Url::remember();
        $params = ['currentDashboard' => $this->getCurrentDashboard()];
        return $this->render('index', $params);
    }
}
