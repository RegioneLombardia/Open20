<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\controllers
 * @category   CategoryName
 */

namespace open20\amos\privileges\controllers;

use open20\amos\dashboard\controllers\base\DashboardController;
use yii\helpers\Url;

class DefaultController extends DashboardController
{
    /**
     * @var string $layout Layout per la dashboard interna.
     */
    public $layout = 'dashboard_interna';

    /**
     * @inheritdoc
     */
    public function init() {

        parent::init();
        $this->setUpLayout();
        // custom initialization code goes here
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        return $this->redirect(['/privileges/privileges/index']);

        Url::remember();
        $params = [
            'currentDashboard' => $this->getCurrentDashboard()
        ];

        return $this->render('index', $params);
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
