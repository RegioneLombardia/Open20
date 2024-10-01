<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\dashboard
 * @category   CategoryName
 */

namespace open20\amos\dashboard\controllers;

use open20\amos\core\controllers\BaseController;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\dashboard\AmosDashboard;
use open20\amos\dashboard\models\AmosUserDashboards;
use open20\amos\dashboard\models\AmosWidgets;
use open20\amos\dashboard\models\search\AmosWidgetsSearch;
use open20\amos\dashboard\assets\ModuleDashboardAsset;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\StringHelper;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\base\Module;
use yii\helpers\Inflector;

class SubDashboardController extends BaseController
{

    use TabDashboardControllerTrait;
    public $layout         = 'main';
    public $widgetsIcon    = [];
    public $widgetsGraphic = [];

    public function init()
    {
        $this->setModelObj(new AmosWidgets());
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
                [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [
//                            'save-dashboard-order',
                                'index',
                                'widgets-by-module'
                            ],
                            'roles' => ['@']
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
    }

    /**
     *
     * @param string|null $module
     * @param string|null $slide
     * @return type
     */
    public function actionIndex($module = null, $slide = null)
    {
        Url::remember();

        $widgetIconSelectable    = AmosWidgetsSearch::selectableIcon(0, null, true)->all();
        $widgetGraphicSelectable = AmosWidgetsSearch::selectableGraphic(0, null, true)->all();
        $widgetSelected          = [];
        $modules                 = [];
        $modulesSubdashboard     = (!empty(\Yii::$app->getModule('dashboard')->modulesSubdashboard) ? \Yii::$app->getModule('dashboard')->modulesSubdashboard
                : null);
        foreach (\Yii::$app->getModules(false) as $key => $module) {
            $class = AmosDashboard::t('amosdashboard', 'Not defined');
            if (is_object($module)) {
                $class = get_class($module);
            } else if (is_array($module)) {
                $class = $module['class'];
            }
            if (!empty($modulesSubdashboard)) {
                if (in_array($key, $modulesSubdashboard)) {
                    $modules[] = ['id' => $key, 'classname' => $class, 'name' => (method_exists($module, 'getModuleName')
                            ? Inflector::camel2words(Inflector::id2camel($module::getModuleName(), '_')) : Inflector::camel2words(Inflector::id2camel($key,
                                '_')))];
                }
            } else {
                $modules[] = ['id' => $key, 'classname' => $class, 'name' => (method_exists($module, 'getModuleName') ? Inflector::camel2words(Inflector::id2camel($module::getModuleName(),
                            '_')) : Inflector::camel2words(Inflector::id2camel($key, '_')))];
            }
        }
        if (Yii::$app->getRequest()->getIsPost()) {
            $data          = \Yii::$app->request->post()['AmosWidgets'];
            $modulePost    = $data['module'];
            $widgetsToSave = $data['classname_subdashboard'];
            try {
                if (strlen(trim($modulePost)) && $this->saveSubDashboardConfig($modulePost, $widgetsToSave)) {
                    Yii::$app->getSession()->addFlash('success',
                        AmosDashboard::t('amosdashboard', 'Configurazioni della sotto-dashboard salvate correttamente'));
                } else {
                    Yii::$app->getSession()->addFlash('danger',
                        AmosDashboard::t('amosdashboard', 'Si è verificato un errore durante il salvataggio dei widget'));
                }
            } catch (Exception $e) {
                Yii::$app->getSession()->addFlash('danger',
                    AmosDashboard::t('amosdashboard',
                        'Si è verificato un errore durante il salvataggio dei widget: <br /><strong>{errorMessage}</strong>',
                        [
                        'errorMessage' => $e->getMessage()
                ]));
            }
        }
        $params = [
            'currentDashboard' => $this->getCurrentDashboard(),
            'modules' => $modules,
            'model' => $this->model,
        ];
        return $this->render('index', $params);
    }

    /**
     *
     * @param string $module
     * @param array $widgets
     * @return boolean
     */
    private function saveSubDashboardConfig($module, $widgets)
    {
        $delete = AmosWidgets::find()
            ->andWhere(['module' => $module, 'sub_dashboard' => 1]);
        $moduleCommunity = \Yii::$app->getModule('community');
        if($moduleCommunity){
            $delete->leftJoin('community_amos_widgets_mm', 'amos_widgets.id = community_amos_widgets_mm.amos_widgets_id')
                ->andWhere(['community_id' => null]);
        }

        foreach ($delete->all() as $d) {
            $d->delete();
        }

        if (!empty($widgets)) {
            foreach ($widgets as $widget) {
                $oldWidget = AmosWidgets::findOne(['classname' => $widget, 'sub_dashboard' => 0]);
                $type      = AmosWidgets::TYPE_ICON;
                if (!empty($oldWidget->type)) {
                    $type = $oldWidget->type;
                }
                $newWidget                = new AmosWidgets();
                $newWidget->classname     = $widget;
                $newWidget->module        = $module;
                $newWidget->sub_dashboard = 1;
                $newWidget->type          = $type;
                $newWidget->save();
            }
        }

        return true;
    }

    /**
     *
     * @return array
     */
    public function actionWidgetsByModule()
    {
        $ret_array = [];
        $selected  = [];

        $parents = $_POST['depdrop_parents'];

        if (!empty($parents)) {
            $module_name = $parents[0];

            $widgetIcons     = AmosWidgetsSearch::selectableIcon(0, null, true);
            $widgetGraphics  = AmosWidgetsSearch::selectableGraphic(0, null, true);
            $iconSelected    = AmosWidgetsSearch::selectableIcon(1, $module_name, true);
            $graphicSelected = AmosWidgetsSearch::selectableGraphic(1, $module_name, true);

            foreach ($widgetIcons->all() as $key => $value) {
                $widget_filename = StringHelper::baseName($value->classname);
                $ret_array[]     = array('id' => $value->classname, 'name' => $widget_filename);
            }
            foreach ($widgetGraphics->all() as $key2 => $value2) {
                $widget_filename2 = StringHelper::baseName($value2->classname);
                $ret_array[]      = array('id' => $value2->classname, 'name' => $widget_filename2);
            }
            foreach ($iconSelected->all() as $k => $v) {
                $selected[] = array('id' => $v->classname);
            }
            foreach ($graphicSelected->all() as $k2 => $v2) {
                $selected[] = array('id' => $v2->classname);
            }

            return Json::encode(['output' => $ret_array, 'selected' => $selected]);

        }
        return Json::encode(['output' => '', 'selected' => '']);
    }
}