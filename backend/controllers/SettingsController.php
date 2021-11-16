<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\controllers
 * @category   CategoryName
 */

namespace backend\controllers;

use open20\amos\admin\AmosAdmin;
use backend\models\Settings;
use open20\amos\core\controllers\BaseController;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use yii\web\Controller;
use yii\db\ActiveQuery;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\gii\CodeFile;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Application;

/**
 * Class DefaultController
 * @package open20\amos\admin\controllers
 */
class SettingsController extends Controller
{
    public $layout = '@vendor/open20/amos-core/views/layouts/main';

    /**
     * Trait used for initialize the news dashboard
     */
    use TabDashboardControllerTrait;

    /**
     * @inheritdoc
     */
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
                            'load-from-file',
                            'update',
                            'enable',
                            'disable',
                            'add-value',
                            'config-test',
                        ],
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get']
                ]
            ]
        ]);
        return $behaviors;
    }

    /**
     * Lists all AmosAdmin models.
     * @return mixed
     */
    public function actionIndex()
    {
        Url::remember();
        return $this->render('index', $params);
    }

    public function actionLoadFromFile()
    {
        $appPath = \Yii::getAlias('@app');


        $config = ArrayHelper::merge(
            require($appPath . '/../common/config/main.php'),
            require($appPath . '/../common/config/main-local.php'),
            require($appPath . '/config/main.php'),
            require($appPath . '/config/main-local.php')
        );

        $treeItems = $this->treeNode($config);

        //Truncate Table
        Settings::deleteAll();

        foreach ($treeItems as $treeItem) {
            $row = new Settings();

            $row->name = $treeItem['name'];
            $row->descriptor = $treeItem['name'];
            $row->route = $treeItem['route'];
            $row->enabled = true;

            switch (gettype($treeItem['value'])) {
                case 'array':
                    $row->type = 'array';
                    $row->value = '';
                    break;
                default:
                    $row->type = gettype($treeItem['value']);
                    $row->value = $treeItem['value'];
            }

            if ($row->validate()) {
                $row->save();
            } else {
                pr($row->getErrors(), $treeItem['name'] . ' - ' . $treeItem['route']);
                die;
            }
        }

        $this->redirect(Url::previous());
    }

    /**
     * @param $array
     * @param string $route
     * @return array
     */
    protected function treeNode($array, $route = '/')
    {
        $items = [];

        foreach ($array as $key => $value) {
            if (!is_array($value)) {
                $items[] = [
                    'name' => $key,
                    'route' => $route,
                    'value' => $value
                ];
            } else {
                $items[] = [
                    'name' => $key,
                    'route' => $route,
                    'value' => []
                ];

                $subTree = $this->treeNode($value, $route . $key . '/');
                $items = array_merge($items, $subTree);
            }
        }

        return $items;
    }

    public function getSettingsAsArray()
    {
        return $this->subnodesOfRoute('/');
    }

    protected function subnodesOfRoute($route)
    {
        /**
         * @var ActiveQuery $settings
         */
        $settings = Settings::findAll(['route' => $route]);

        $array = [];

        foreach ($settings as $row) {
            if ($row->enabled) {
                switch ($row->type) {
                    case 'integer':
                        $array[$row->name] = (int)$row->value;
                        break;
                    case 'boolean':
                        $array[$row->name] = (bool)$row->value;
                        break;
                    case 'array':
                        $array[$row->name] = $this->subnodesOfRoute($row->route . $row->name . '/');
                        break;
                    default:
                        $array[$row->name] = $row->value;
                }
            }
        }

        return $array;
    }

    public function actionUpdate($id)
    {
        Url::remember();

        //Find this setting
        $model = Settings::findOne($id);

        $configurations = Settings::findAll([
            'route' => $model->route . $model->name . '/'
        ]);

        return $this->render('update', [
            'model' => $model,
            'configurations' => $configurations
        ]);
    }

    public function actionEnable($id)
    {
        $model = Settings::findOne($id);

        $model->enabled = true;
        $model->save();

        $this->redirect(Url::previous());
    }

    public function actionDisable($id)
    {
        $model = Settings::findOne($id);

        $model->enabled = false;
        $model->save();

        $this->redirect(Url::previous());
    }

    public function actionConfigTest($appName = 'RunTestApp')
    {
        if (\Yii::$app->name == $appName) {
            return true;
        }

        $newConfig = $this->getSettingsAsArray();
        $newConfig['name'] = $appName;

        $parsableArray = var_export($newConfig, true);

        $configLocation = \Yii::getAlias('@app') . '/config/config-test.php';

        file_put_contents($configLocation, "<?php\n return $parsableArray;");

        $url = Url::to('/index-test.php', true);

        $exitCode = (bool)file_get_contents($url);

        return $this->render('config-test', [
            'model' => $this->model,
            'result' => $exitCode
        ]);
    }

    public function actionAddValue($id) {
        //Find this setting
        $model = Settings::findOne($id);


        
        $this->render('add-value', ['model' => $model]);
    }
}
