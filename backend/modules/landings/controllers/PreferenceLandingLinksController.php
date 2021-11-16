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
use backend\modules\landings\models\PreferenceLandingLink;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;

/**
 * Class PreferenceLandingLinksController
 * This is the class for controller "PreferenceLandingLinksController".
 * @package backend\modules\landings\controllers
 */
class PreferenceLandingLinksController extends \backend\modules\landings\controllers\base\PreferenceLandingLinksController
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
                            'create',
                            'update',
                            'delete',
                            'view',
                            'sort-links',
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
     * @param $direction
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function actionSortLinks($id, $direction = 'up'){
        /** @var PreferenceLandingLink $link */
        $link = PreferenceLandingLink::findOne(['id' => $id]);
        if (empty($link)) {
            throw new BadRequestHttpException();
        }

        $ok = $this->sortLinks($link->id, $link->preference_landing_id, $direction);
        if (!$ok) {
            \Yii::$app->session->addFlash('danger', 'Errore nell\'ordinamento dei documenti');
            return $this->redirect(['/landings/preference-landing/update', 'id' => $link->preference_landing_id]);
        }
        return $this->redirect(['/landings/preference-landing/update', 'id' => $link->preference_landing_id, '#' => 'pos-links']);
    }

    private function sortLinks($id, $landingId, $direction){
        try {
            $elements = PreferenceLandingLink::find()
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
            $link = PreferenceLandingLink::findOne($id);
            $link->sort_value = $i;
            $link->save(false);
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
