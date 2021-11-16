<?php

namespace open20\amos\audit\controllers;

use open20\amos\audit\components\Helper;
use open20\amos\audit\components\web\Controller;
use open20\amos\audit\models\AuditMail;
use open20\amos\audit\models\AuditMailSearch;

use Yii;
use yii\web\NotFoundHttpException;

/**
 * MailController
 * @package open20\amos\audit\controllers
 */
class MailController extends Controller
{
    /**
     * Lists all AuditMail models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuditMailSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single AuditMail model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = AuditMail::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('The requested mail does not exist.');
        }
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single AuditMail model's HTML.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionViewHtml($id)
    {
        $model = AuditMail::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('The requested mail does not exist.');
        }
        $this->layout = false;
        return $this->render('view-html', [
            'model' => $model,
        ]);
    }

    /**
     * Download an AuditMail file as eml.
     * @param $id
     * @throws NotFoundHttpException
     */
    public function actionDownload($id)
    {
        $model = AuditMail::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('The requested mail does not exist.');
        }
        Yii::$app->response->sendContentAsFile(Helper::uncompress($model->data), $model->id . '.eml');
    }
}
