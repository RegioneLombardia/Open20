<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\moodle\controllers
 * @category   CategoryName
 */

namespace open20\amos\moodle\controllers;

use open20\amos\dashboard\controllers\base\DashboardController;

class DefaultController extends DashboardController
{
    /**
     * @var string $layout Layout per la dashboard interna.
     */
    public $layout = "@vendor/open20/amos-core/views/layouts/dashboard_interna";
    
    /**
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
   
        return $this->redirect(['/moodle/course/index']);

    }
    
}
