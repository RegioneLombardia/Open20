<?php

namespace open20\amos\utility\controllers;

use open20\amos\core\controllers\BackendController;
use open20\amos\core\module\ModuleInterface;
use open20\amos\core\utilities\ClassUtility;
use open20\amos\utility\Module;
use Yii;
use yii\filters\AccessControl;

/**
 * Class DefaultController
 * @package open20\amos\utility\controllers
 */
class DefaultController extends BackendController
{
    public $layout = "main";

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
                        'allow' => true,
                        'actions' => [
                            'index',
                            'reset-dashboard-by-module'
                        ],
                        'roles' => ['ADMIN']
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->setUpLayout();
        // custom initialization code goes here
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $modules = \Yii::$app->getModules();

        $moduleNames = [];
        foreach ($modules as $key => $module) {
            $moduleNames[$key] = $key;
        }
        return $this->render('index', ['moduleNames' => $moduleNames]);
    }

    /**
     * @return \yii\web\Response
     * @throws \ReflectionException
     */
    public function actionResetDashboardByModule()
    {
        $classname = 'open20\amos\dashboard\utility\DashboardUtility';
        if (Yii::$app->request->post('moduleName')) {
            $moduleName = Yii::$app->request->post('moduleName');
        }
        try {
            if (ClassUtility::classExist($classname)) {
                if (empty($moduleName)) {
                    $modules = \Yii::$app->getModules();
                    if (!empty($modules)) {
                        foreach ($modules as $id => $module) {
                            $moduleObj = Yii::$app->getModule($id);
                            $class = new \ReflectionClass($moduleObj);
                            if ($class->implementsInterface(ModuleInterface::class)) {
                                $classname::resetDashboardsByModule($moduleObj->getModuleName());
                            }
                        }
                        Yii::$app->getSession()->addFlash('success', Module::t('amosutility', 'Dashboard resetted'));
                    }
                } else {
                    $classname::resetDashboardsByModule($moduleName);
                    Yii::$app->getSession()->addFlash('success', $moduleName . ' ' . Module::t('amosutility', 'Dashboard resetted'));
                }
            }
        } catch (\Exception $ex) {
            Yii::$app->getSession()->addFlash('error', Module::t('amosutility', 'Dashboard not resetted'));
            Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
        }

        return $this->redirect('index');
    }
}
