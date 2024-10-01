<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\dashboard\controllers
 * @category   CategoryName
 */

namespace open20\amos\dashboard\controllers;

use open20\amos\core\applications\CmsApplication;
use open20\amos\core\helpers\BreadcrumbHelper;
use open20\amos\dashboard\controllers\base\DashboardController;
use yii\helpers\Url;

/**
 * Class DefaultController
 * @package open20\amos\dashboard\controllers
 */
class DefaultController extends DashboardController
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        $this->setUpLayout();
    }
    
    /**
     * @return string
     */
    public function actionIndex()
    {
        if ((\Yii::$app->hasMethod('isCmsApplication') && \Yii::$app->isCmsApplication()) || (\Yii::$app instanceof CmsApplication)) {
            return $this->goHome();
        }
        
        Url::remember();
        
        $this->setUpLayout('dashboard');
        
        $moduleCwh = \Yii::$app->getModule('cwh');
        if (isset($moduleCwh)) {
            $moduleCwh->resetCwhScopeInSession();
        }
        
        BreadcrumbHelper::reset();
        
        $params = [
            'currentDashboard' => $this->getCurrentDashboard()
        ];
        
        return $this->render('index', $params);
    }
}
