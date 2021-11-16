<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\dashboard\controllers
 * @category   CategoryName
 */

namespace open20\amos\dashboard\controllers;

use open20\amos\core\helpers\BreadcrumbHelper;
use open20\amos\dashboard\controllers\base\DashboardController;
use yii\helpers\Url;

/**
 * Class DefaultController
 * @package open20\amos\dashboard\controllers
 */
class DefaultController extends DashboardController {

  /**
   * @inheritdoc
   */
  public function init() {
    parent::init();
    
    $this->setUpLayout();
  }

  /**
   * @return string
   */
  public function actionIndex() {
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
