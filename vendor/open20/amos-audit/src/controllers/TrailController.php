<?php

namespace open20\amos\audit\controllers;

use open20\amos\audit\components\web\Controller;
use open20\amos\audit\models\AuditTrail;
use open20\amos\audit\models\AuditTrailSearch;

use Yii;
use yii\web\NotFoundHttpException;

/**
 * TrailController
 * @package open20\amos\audit\controllers
 */
class TrailController extends Controller
{
    /**
     * Lists all AuditTrail models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuditTrailSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }

    /**
     * Displays a single AuditTrail model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = AuditTrail::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('The requested trail does not exist.');
        }
        return $this->render('view', [
            'model' => $model,
        ]);
    }
}
