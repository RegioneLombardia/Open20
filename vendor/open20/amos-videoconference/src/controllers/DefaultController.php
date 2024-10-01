<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\videoconference\controllers
 * @category   CategoryName
 */

namespace open20\amos\videoconference\controllers;

use open20\amos\dashboard\controllers\base\DashboardController;
use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

class DefaultController extends DashboardController {

    /**
     * @var string $layout Layout per la dashboard interna.
     */
    public $layout = "@vendor/open20/amos-core/views/layouts/dashboard_interna";
    
    
    /**
     * Lists all Videoconference models.
     * @return mixed
     */
    public function actionIndex() {
        return $this->redirect(['/videoconference/videoconf/index']);

       /* Url::remember();

        $params = [
            'currentDashboard' => $this->getCurrentDashboard()
        ];

        return $this->render('index', $params);*/
       
    }

}
