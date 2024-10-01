<?php
namespace open20\amos\utility\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use Yii;
use AD7six\Dsn\Dsn;

class DatabaseController extends Controller
{
    
    /**
     * Disable layout
     * @var bool
     */
    public $layout = false;
    
    /**
     * Disable CSRF
     */
    public function init() {
        parent::init();
        $this->enableCsrfValidation = false;
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
                        ],
                        'roles' => ['ACCESS_THE_BASEMENT']
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['post', 'get']
                ]
            ]
        ];
    }



    /*
     * 
     */
    public function actionIndex()
    {
        //Retreive the adminer file location
        $src = Yii::getAlias('@vendor/open20/amos-utility/src');

        //Configured DSN
        $dsn = Yii::$app->db->dsn;

        //Preg match from dsn data
        preg_match('/host=(?P<host>[^;]*)?/i', $dsn, $matchHost);
        preg_match('/dbname=(?P<dbname>[^;]*)?/i', $dsn, $matchDbName);
        preg_match('/port=(?P<port>[^;]*)?/i', $dsn, $matchPort);

        //Disable yii2 things
        Yii::$app->response->headers->removeAll();
        Yii::$app->response->detachBehaviors();
        Yii::$app->errorHandler->unregister();
        Yii::$app->response->isSent = true;

        //Configured host withhout port
        $host = $matchHost['host'];

        //Configured database name
        $database = $matchDbName['dbname'];

        //Configured database name
        $port = isset($matchPort['port']) ? $matchPort : '';

        //$dsn is an instance of \AD7six\Dsn\Db\MysqlDsn;
        $dsnParsed = Dsn::parse($dsn)->toArray();

        //Override GET PARAMS
        if (empty($_GET)) {
            $_POST['auth'] = [
                'driver' => 'server',
                'server' => $host . ($port ? ':' . $port : ''),
                'username' => Yii::$app->db->username,
                'password' => Yii::$app->db->password,
                'db' => $database,
                'permanent' => true
            ];

        }

        //Include adminer application
        require $src . '/libs/adminer.php';

        //Stop Yii Execution
        die();
    }

    /**
     * @param null $layout
     * @return bool
     */
    public function setUpLayout($layout = null){
        if ($layout === false){
            $this->layout = false;
            return true;
        }
        $module = \Yii::$app->getModule('layout');
        if(empty($module)){
            $this->layout =  '@vendor/open20/amos-core/views/layouts/' . (!empty($layout) ? $layout : $this->layout);
            return true;
        }
        $this->layout = (!empty($layout)) ? $layout : $this->layout;
        return true;
    }
}