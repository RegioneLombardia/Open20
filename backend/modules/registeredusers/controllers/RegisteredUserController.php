<?php



/**

 * Lombardia Informatica S.p.A.

 * OPEN 2.0

 *




 *

 * @package    backend\modules\registeredusers


 */



namespace backend\modules\registeredusers\controllers;

use backend\modules\registeredusers\models\RegisteredUser;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use open20\amos\comuni\models\IstatComuni;
use open20\amos\comuni\models\IstatProvince;
use Yii;
use backend\modules\registeredusers\utility\NotificationPushUtility;

/**

 * Class OperatorController

 * This is the class for controller "OperatorController".

 * @package backend\modules\registeredusers

 */

class RegisteredUserController extends \backend\modules\registeredusers\controllers\base\RegisteredUserController
{

        /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'get-comuni-by-id-provincia',
                            'test-app-notify'
                        ],
                        'roles' => ['PC_ADMIN', 'PC_BASIC_USER']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get']
                ]
            ]
        ]);
    }


    /**
     * Metodo per l'estrazione dei communi associati alla provincia
     * chaiamata ajax
     *
     * @return json | comuni
     */
    public function actionGetComuniByIdProvincia(){

        // estrazione del record della provincia
        $provincia = IstatProvince::findOne(\Yii::$app->request->post("provincia_residenza_id"));

        // estrazione dei comuni associati alla provincia
        $comuni_provincia = IstatComuni::find()->where([
            'istat_province_id' => $provincia->id,
        ])->orderBy(['nome' => SORT_ASC] )->asArray()->all();

        return \json_encode($comuni_provincia);
    }


    public function actionTestAppNotify($id) {
        // disabilito la funzionalità
        Yii::$app->getSession()->addFlash('info', \Yii::t('campain', "Funzionalità disabilitata")); return $this->redirect(Yii::$app->request->referrer);

        $model = RegisteredUser::findOne(['id' => $id]);
        $ret = NotificationPushUtility::sendNotification($model->user_id, 'Test', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,', 'preference_campain_channel_mm', 0);

        if ($ret) {
            Yii::$app->getSession()->addFlash('info', \Yii::t('campain', "Notifica inviata all'utente: " . $model->nomeCognome));
        } else {
            Yii::$app->getSession()->addFlash('danger', \Yii::t('campain', "Non innviata"));
        }

        return $this->redirect(Yii::$app->request->referrer);
    }


}
