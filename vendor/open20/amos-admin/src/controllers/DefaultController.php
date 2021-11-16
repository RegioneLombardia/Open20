<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\controllers
 * @category   CategoryName
 */

namespace open20\amos\admin\controllers;

use open20\amos\dashboard\controllers\base\DashboardController;
use open20\amos\admin\AmosAdmin;
use yii\helpers\Url;

/**
 * Class DefaultController
 * @package open20\amos\admin\controllers
 */
class DefaultController extends DashboardController
{
    /**
     * @var string $layout Layout per la dashboard interna.
     */
    public $layout = "dashboard_interna";

    /**
     * @inheritdoc
     */
    public function init() {

        parent::init();
        $this->setUpLayout();
        // custom initialization code goes here
    }

    /**
     * Lists all AmosAdmin models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->redirect(['/'.AmosAdmin::getModuleName().'/user-profile/validated-users']);
        Url::remember();
        $params = ['currentDashboard' => $this->getCurrentDashboard()];
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
