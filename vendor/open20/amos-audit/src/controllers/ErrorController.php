<?php

namespace open20\amos\audit\controllers;

use open20\amos\audit\components\web\Controller;
use open20\amos\audit\models\AuditError;
use open20\amos\audit\models\AuditErrorSearch;

use Yii;
use yii\web\NotFoundHttpException;

/**
 * ErrorController
 * @package open20\amos\audit\controllers
 */
class ErrorController extends Controller
{
    /**
     * Lists all AuditError models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuditErrorSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }

    /**
     * Displays a single AuditError model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = AuditError::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('The requested error does not exist.');
        }
        return $this->render('view', [
            'model' => $model,
        ]);
    }
}
