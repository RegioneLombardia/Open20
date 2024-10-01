<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\tag
 * @category   CategoryName
 */

namespace open20\amos\tag\controllers;

use open20\amos\dashboard\controllers\base\DashboardController;
use yii\helpers\Url;

/**
 * 
 */
class DefaultController extends DashboardController
{

    /**
     * @var string $layout Layout per la dashboard interna.
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
     * Lists all NewsAllegati models.
     * @return mixed
     */
    public function actionIndex()
    {
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

        $module = \Yii::$app->getModule('layout');
        if (empty($module)) {
            $this->layout = '@vendor/open20/amos-core/views/layouts/'
                . (!empty($layout) ? $layout : $this->layout);
            return true;
        }

        $this->layout = (!empty($layout)) ? $layout : $this->layout;
        return true;
    }

}