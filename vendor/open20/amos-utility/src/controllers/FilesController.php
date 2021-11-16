<?php
namespace open20\amos\utility\controllers;

use open20\amos\utility\Module;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;
use yii\web\Controller;
use Yii;
use AD7six\Dsn\Dsn;

class FilesController extends Controller
{
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
                        'roles' => ['ADMIN']
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    '*' => ['post', 'get']
                ]
            ]
        ];
    }

    public $layout = "main";

    /**
     * @inheritdoc
     */
    public function init() {

        parent::init();
        $this->setUpLayout();
        // custom initialization code goes here
    }

    /**
     * @param null $layout
     * @return bool
     */
    public function setUpLayout($layout = null){
        $this->layout = false;
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $src = Yii::getAlias('@vendor/open20/amos-utility/src');

        define('FM_EMBED', true);
        define('FM_ROOT_PATH', Yii::getAlias('@app').'/..');
        define('FM_SELF_URL', '/' . Yii::$app->controller->getRoute());
        define('FM_ROOT_URL', '/' . Yii::$app->controller->getRoute());
        $_SERVER["PHP_SELF"] = '/' . Yii::$app->controller->getRoute();

        require $src . '/libs/tinyfilemanager.php';
        die;
    }
}