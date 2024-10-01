<?php

namespace open20\amos\utility\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Class ConsoleController
 * @package open20\amos\utility\controllers
 */
class ConsoleController extends Controller
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
                        'roles' => ['ACCESS_THE_BASEMENT']
                    ],
                ],
            ],
        ];
    }

    public function actionIndex($cmd = '')
    {
        return $this->render('index', [
            'cmd' => $cmd
        ]);
    }

    /**
     *
     * @param string $cmd
     */
    public function actionRunCmd($cmd)
    {
        $output = '';

        chdir(Yii::getAlias('@vendor/..'));
        $output = shell_exec($cmd);

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
