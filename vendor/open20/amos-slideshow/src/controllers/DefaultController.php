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

use open20\amos\dashboard\controllers\base\DashboardController;
use yii\helpers\Url;

/**
 * Class DefaultController
 * @package open20\amos\slideshow\controllers
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
}
