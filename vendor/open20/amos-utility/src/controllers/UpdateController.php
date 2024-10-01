<?php

namespace open20\amos\utility\controllers;

use open20\amos\core\controllers\BackendController;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\httpclient\Client;

/**
 * Class ConsoleController
 * @package open20\amos\utility\controllers
 */
class UpdateController extends BackendController
{
    public $layout = 'main';

    /**
     * Disable CSRF
     */
    public function init()
    {
        parent::init();
        $this->enableCsrfValidation = false;
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
                        'allow' => true,
                        'actions' => [
                            'index',
                            'start',
                        ],
                        'roles' => ['ACCESS_THE_BASEMENT']
                    ],
                ],
            ],
        ];
    }

    public function actionIndex($force = false)
    {
        //Override the base url for better UrlPath syntax
        $client = new Client([
                                 'baseUrl' => $this->module->params['master_domain'],
                                 'transport' => 'yii\httpclient\CurlTransport'
                             ]);

        if (!isset($this->module->params['platform_id'])) {
            throw new Exception("Missin Platform ID");
        }

        if (!isset($this->module->params['key'])) {
            throw new Exception("Missin Key");
        }

        $data = [
            'platformId' => $this->module->params['platform_id'],
            'key' => $this->module->params['key']
        ];

        if ($force) {
            $data['force'] = 'true';
        }

        $response = $client->createRequest()
            ->setUrl(['/platforms/platforms/prepare-update'])
            ->setData($data)
            ->send();

        if ($force) {
            return $this->redirect('index');
        }

        $update = $response->data;

        return $this->render('index', [
            'update' => $update
        ]);
    }

    /**
     *
     */
    public function actionStart()
    {
        if (!$this->module->params['php_executable']) {
            throw new Exception('Missing PHP Executable Param');
        }

        if (!$this->module->params['environment']) {
            throw new Exception('Missing Environment Param');
        }

        //Move to root
        chdir(Yii::getAlias('@vendor/..'));

        $phpBin = $this->module->params['php_executable'];
        $env = $this->module->params['environment'];

        $command = "{$phpBin} vendor/bin/dep -f";
        $command .= '"vendor/open20/amos-utility/src/deployer/deploy.php" ';
        $command .= "deploy --env={$env} --php={$phpBin} -vvv";

        return $this->render('start', [
            'command' => $command
        ]);
    }
}
