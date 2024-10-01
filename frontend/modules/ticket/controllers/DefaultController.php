<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\controllers
 * @category   CategoryName
 */

namespace frontend\modules\ticket\controllers;

use open20\amos\dashboard\controllers\base\DashboardController;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use Yii;

/**
 * Class DefaultController
 * @package open2\amos\ticket\controllers
 */
class DefaultController extends \open2\amos\ticket\controllers\DefaultController
{

  public function behaviors()
    {
        $behaviors = ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                        ],
                        'roles' => ['?']
                    ],
                ]
            ],
        ]);
        return $behaviors;
    }
    /**
     * @return \yii\web\Response
     */
    public function actionIndex()
    {
      if (Yii::$app->getUser()->can('REFERENTE_TICKET') || Yii::$app->getUser()->can('AMMINISTRATORE_TICKET')) {
          return $this->redirect(['/ticket/ticket-faq/index']);
      } else {
          return $this->redirect(['/ticket/assistenza/cerca-faq']);
      }
    }
}
