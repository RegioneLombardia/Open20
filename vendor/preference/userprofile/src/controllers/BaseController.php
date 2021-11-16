<?php
namespace preference\userprofile\controllers;

use open20\amos\core\controllers\BackendController;
use preference\userprofile\utility\TargetTargetUtility;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    [NAMESPACE_HERE]
 * @category   CategoryName
 */

/**
 * Description of BaseController
 *
 */
class BaseController extends BackendController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['test'],
                'rules' => [
                    [
                        'actions' => ['test'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
                ],
            ],
        ];
    }

    public function actionTest(){
        return $this->render("index");
    }
}