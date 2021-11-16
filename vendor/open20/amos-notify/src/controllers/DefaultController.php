<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notificationmanager\controllers
 */

namespace open20\amos\notificationmanager\controllers;

use open20\amos\dashboard\controllers\base\DashboardController;

/**
 * Class DefaultController
 * @package open20\amos\notificationmanager\controllers
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
    public function init()
    {
        parent::init();
        $this->setUpLayout();
    }
    
    /**
     * Lists all models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->redirect('/notify/newsletter/index');
    }
}
