<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\controllers
 * @category   CategoryName
 */

namespace open20\amos\layout\controllers;

use open20\amos\admin\models\UserProfileReactivationRequest;
use open20\amos\core\controllers\BackendController;
use open20\amos\core\forms\FirstAccessForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;


/**
 * Class SecurityController
 * @package open20\amos\admin\controllers
 */
class ErrorController extends BackendController
{
    /**
     * @var string $layout
     */
    public $layout = 'error';


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setUpLayout();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'error',
                        ],
                        'allow' => true,
                    ],

                ],
            ],
        ];
    }


    public function actionIndex(){
        $this->redirect(['errors']);
    }

    /**
     * Maintenance action
     * @return string
     */
    public function actionError()
    {
        $exception = \Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return $this->render('errors', ['exception' => $exception]);
        }
    }

}
