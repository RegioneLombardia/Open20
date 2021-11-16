<?php

namespace open20\amos\utility\controllers;

use open20\amos\core\controllers\BackendController;
use Yii;
use yii\filters\AccessControl;

/**
 * Class ConsoleController
 * @package open20\amos\utility\controllers
 */
class ConsoleController extends BackendController
{
    public $layout = 'main';
    private $rootDir = '';

    /**
     * Disable CSRF
     */
    public function init()
    {
        parent::init();
        $this->enableCsrfValidation = false;
        $this->setUpLayout();

        //The project root dir
        $this->rootDir = Yii::getAlias('@vendor/..');
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
                        'allow' => true,
                        'actions' => [
                            'index',
                            'migrate',
                            'run-cmd',
                        ],
                        'roles' => ['ADMIN']
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     *
     * @param string $cmd
     */
    public function actionRunCmd($cmd)
    {
        $output = '';
        Yii::$app->consoleRunner->run($cmd, $output);
        return $this->render('output', ['output' => $output]);
    }

    public function actionMigrate($force = false)
    {
        $migrate = new MigrationsController('migrate', $this->module);
        $migrations = $migrate->getMigrations();

        try {
            $result = $force ? (string) $migrate->applyMigrations() : null;
        } catch (\Exception $e) {
            $result = $e->getMessage();
        }

        return $this->render('migrate', [
            'migrations' => $migrations,
            'result' => $result,
            'force' => $force
        ]);
    }
}
