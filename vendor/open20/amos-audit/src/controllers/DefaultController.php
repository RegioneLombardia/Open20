<?php

namespace open20\amos\audit\controllers;

use open20\amos\audit\components\panels\RendersSummaryChartTrait;
use open20\amos\audit\components\web\Controller;
use open20\amos\audit\models\AuditEntry;
use Yii;

/**
 * DefaultController
 * @package open20\amos\audit\controllers
 */
class DefaultController extends Controller
{
    use RendersSummaryChartTrait;

    /**
     * Module Default Action.
     * @return mixed
     */
    public function actionIndex()
    {
        $chartData = $this->getChartData();
        return $this->render('index', ['chartData' => $chartData]);
    }

    protected function getChartModel()
    {
        return AuditEntry::className();
    }
}
