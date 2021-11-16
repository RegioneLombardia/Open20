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
class MaintenanceController extends BackendController
{
    /**
     * @var string $layout
     */
    public $layout = 'maintenance';


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
                            'maintenance',
                        ],
                        'allow' => true,
                    ],

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post', 'get'],
                ],
            ],
        ];
    }


    public function actionIndex(){

        $this->redirect(['maintenance']);
    }

    /**
     * Maintenance action
     * @return string
     */
    public function actionMaintenance()
    {
//        $this->setUpLayout('maintenance');
    $module = \Yii::$app->getModule($this->module->id);
    $view = (!empty($module->viewMaintenanceMode)? $module->viewMaintenanceMode : 'maintenance');
        return $this->render($view);

    }

}
