<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    backend\modules\landings\controllers
 */

namespace backend\modules\landings\controllers;
use backend\modules\landings\models\base\PreferenceLandingDocumenti;
use backend\modules\landings\models\PreferenceLanding;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * Class PreferenceLandingController
 * This is the class for controller "PreferenceLandingController".
 * @package backend\modules\landings\controllers
 */
class PreferenceLandingController extends \backend\modules\landings\controllers\base\PreferenceLandingController
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
                            'publish',
                            'unpublish',
                        ],
                        'roles' => ['PC_ADMIN']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'order-documents',
                        ],
                        'roles' => ['PC_BASIC_USER']
                    ],
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
     * @param $id
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionPublish($id){
        $this->model = $this->findModel($id);
        if($this->model->status == PreferenceLanding::PREFERENCE_LANDING_STATUS_DRAFT){
            $this->model->status = PreferenceLanding::PREFERENCE_LANDING_STATUS_PUBLISHED;
            if($this->model->save(false)){
                \Yii::$app->session->addFlash('success', "La landing è stata pubblicata con successo, da questo momento sarà visibile sul portale.");
            }
        }
        return $this->redirect('/landings/preference-landing/index');
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUnpublish($id){
        $this->model = $this->findModel($id);
        if($this->model->status == PreferenceLanding::PREFERENCE_LANDING_STATUS_PUBLISHED){
            $this->model->status = PreferenceLanding::PREFERENCE_LANDING_STATUS_DRAFT;
            if($this->model->save(false)){
                \Yii::$app->session->addFlash('success', "La landing non è più pubblica, da questo momento non sarà visibile sul portale.");
            }
        }
        return $this->redirect('/landings/preference-landing/index');
    }

    /**
     * @param $id
     * @param $landingId
     * @param $direction
     * @return \yii\web\Response
     */
    public function actionOrderDocuments($id, $landingId, $direction)
    {
        $docMm = PreferenceLandingDocumenti::find()
            ->andWhere(['preference_landing_id' => $landingId])
            ->andWhere(['documenti_id' => $id])
            ->one();

        $ok = $this->orderDocumentsMm($docMm->id, $landingId, $direction);
        if (!$ok) {
            \Yii::$app->session->addFlash('danger', 'Errore nell\'ordinamento dei documenti');
            return $this->redirect(['/landings/preference-landing/update', 'id' => $landingId]);
        }
        return $this->redirect(['/landings/preference-landing/update', 'id' => $landingId, '#' => 'pos-allegati']);
    }

    /**
     *
     * @param integer $id of PreferenceLandingDocumenti
     * @param integer $landingId
     * @param string $direction 'up' or 'down'
     * @return boolean
     */
    protected function orderDocumentsMm($id, $landingId, $direction = 'up')
    {
        try {
            $elements = PreferenceLandingDocumenti::find()
                ->andWhere(['preference_landing_id' => $landingId])
                ->orderBy('sort_value')->all();

            $orderList = [];
            foreach ($elements as $element) {
                $orderList [] = $element->id;
            }

            //find the element  in the ids array and move it up or down
            $indexElemToMove = array_search($id, $orderList);
            if ($direction == 'up') {
                $orderList = $this->up($orderList, $indexElemToMove);
            } else {
                $orderList = $this->down($orderList, $indexElemToMove);
            }

            //save the element with the new order
            $this->resetNumberOrder($orderList);
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * @param $orderList
     */
    public function resetNumberOrder($orderList)
    {
        $i = 1;
        foreach ($orderList as $id) {
            $documentMm        = PreferenceLandingDocumenti::findOne($id);
            $documentMm->sort_value = $i;
            $documentMm->save(false);
            $i++;
        }
    }

    /**
     * Move the element in the x position up
     * @param $array
     * @param $x
     * @return array
     */
    public function up($array, $x)
    {
        if ($x > 0 and $x < count($array)) {
            $b   = array_slice($array, 0, ($x - 1), true);
            $b[] = $array[$x];
            $b[] = $array[$x - 1];
            $b   += array_slice($array, ($x + 1), count($array), true);
            return($b);
        } else {
            return $array;
        }
    }

    /** Move the element in the x position down
     * @param $array
     * @param $x
     * @return array
     */
    public function down($array, $x)
    {
        if (count($array) - 1 > $x) {
            $b   = array_slice($array, 0, $x, true);
            $b[] = $array[$x + 1];
            $b[] = $array[$x];
            $b   += array_slice($array, $x + 2, count($array), true);
            return($b);
        } else {
            return $array;
        }
    }




}
