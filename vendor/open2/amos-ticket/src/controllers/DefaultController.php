<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\controllers
 * @category   CategoryName
 */

namespace open2\amos\ticket\controllers;

use open20\amos\dashboard\controllers\base\DashboardController;
use Yii;

/**
 * Class DefaultController
 * @package open2\amos\ticket\controllers
 */
class DefaultController extends DashboardController
{
    /**
     * @var string $layout Layout for internal dashboard.
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
     * @return \yii\web\Response
     */
    public function actionIndex()
    {
        if (Yii::$app->getUser()->can('REFERENTE_TICKET') || Yii::$app->getUser()->can('AMMINISTRATORE_TICKET')) {
            return $this->redirect(['/ticket/ticket/ticket-waiting']);
        } else {
            return $this->redirect(['/ticket/assistenza/cerca-faq']);
        }
    }
}
