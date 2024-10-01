<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi
 * @category   CategoryName
 */

namespace open20\amos\sondaggi\controllers;

use open20\amos\dashboard\controllers\base\DashboardController;
use yii\helpers\Url;

/**
 * Class DefaultController
 * @package open20\amos\discussioni\controllers
 */
class DefaultController extends DashboardController
{
    /**
     * @var string $layout
     */
    public $layout = 'list';

    /**
     * @inheritdoc
     */
    public function init() {

        parent::init();
        $this->setUpLayout();
        // custom initialization code goes here
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $this->redirect(['sondaggi/index']);
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
                $this->layout = '@vendor/open20/amos-core/views/layouts/' . (!empty($layout) ? $layout : $this->layout);
            }
        }

        return true;
    }
}
