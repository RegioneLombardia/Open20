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

use open20\amos\core\controllers\CrudController;
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
use open20\amos\core\widget\WidgetAbstract;

class ManagerController extends CrudController
{

    use TabDashboardControllerTrait;
    /**
     * @var string $layout
     */
    public $layout         = 'main';
    public $widgetsIcon    = [];
    public $widgetsGraphic = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->initDashboardTrait();

        $this->setModelObj(new AmosWidgets());
        $this->setModelSearch(new AmosWidgetsSearch());

        ModuleDashboardAsset::register(Yii::$app->view);

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosDashboard::t('amosdashboard',
                    '{iconaTabella}'.Html::tag('p', AmosDashboard::t('amosdashboard', 'Tabella')),
                    [
                    'iconaTabella' => AmosIcons::show('view-list-alt')
                ]),
                'url' => '?currentView=grid'
            ],
            'icon' => [
                'name' => 'icon',
                'label' => AmosDashboard::t('amosdashboard',
                    '{iconaElenco}'.Html::tag('p', AmosDashboard::t('amosdashboard', 'Icone')),
                    [
                    'iconaElenco' => AmosIcons::show('grid')
                ]),
                'url' => '?currentView=icon'
            ],
        ]);

        parent::init();

        if(!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $this->view->pluginIcon = 'dash dash-dashboard';
        }

        $this->setUpLayout();

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
                                'save-dashboard-order',
                                'index'
                            ],
                            'roles' => ['CAN_MANAGE_DASHBOARD']
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

    public function actionSaveDashboardOrder()
    {

        try {
            $userDashboard = AmosUserDashboards::findOne(Yii::$app->getRequest()->post('currentDashboardId'));
            //se è una chiamata ajax
            if (!Yii::$app->request->isAjax) {
                throw new Exception(AmosDashboard::t('amosdashboard',
                    'Impossibile salvare la configurazione selezionata'));
            }
            $userDashboard->unlinkAll('amosWidgetsClassnames', true);
            $newDashboard = Yii::$app->getRequest()->post('amosWidgetsClassnames');

            $tmp = [];
            foreach ($newDashboard as $k => $classname) {
                $widget = AmosWidgets::findOne(['classname' => $classname, 'sub_dashboard' => 0]);
                
                if ((!empty($widget->id) && !(isset($tmp[$classname])))) {
                    $tmp[$classname] = $classname;
                    $userDashboard->link(
                        'amosWidgetsClassnames',
                        $widget,
                        [
                            'amos_widgets_id' => $widget->id,
                            'order' => $userDashboard->getMaxOrder() + 2
                        ]
                    );
                }
            }

            return json_encode(array("success" => true));
        } catch (ErrorException $e) {
            return json_encode(array("success" => false, "error" => $e->getMessage()));
        }
    }

    public function actionIndex($module = null, $slide = null)
    {
        Url::remember();

        $this->setUpLayout('list');
      
        $widgetIconSelectable = AmosWidgetsSearch::selectableIcon()->all();
        $widgetSelected       = [];

        if (Yii::$app->getRequest()->getIsPost()) {
            try {
                if ($this->saveDashboardConfig()) {
                    Yii::$app->getSession()->addFlash('success',
                        AmosDashboard::t('amosdashboard', 'Configurazioni dalla dashboard salvate correttamente'));
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

        /**
         * TODO
         * gestire meglio la parte dei widget selezionati
         */
        foreach ($widgetIconSelectable as $widget) {
            if ($this->getCurrentDashboard()->getAmosWidgetsSelectedIcon()->andWhere(['classname' => $widget->classname])->count()) {
                $widgetSelected[] = $widget->classname;
            }
        }

        $widgetGraphicSelectable = AmosWidgetsSearch::selectableGraphic()->all();

        foreach ($widgetGraphicSelectable as $widget) {
            if ($this->getCurrentDashboard()->getAmosWidgetsSelectedGraphic()->andWhere(['classname' => $widget->classname])->count()) {
                $widgetSelected[] = $widget->classname;
            }
        }

        $providerIcon = $this->modelSearch->widgetIcons(Yii::$app->request->getQueryParams());

        $providerGraphic = new ArrayDataProvider([
            'allModels' => $widgetGraphicSelectable,
            'pagination' => false,
        ]);

        //$this->setCurrentView($this->getAvailableView('grid'));
        $params = [
            'currentDashboard' => $this->getCurrentDashboard(),
            'widgetIconSelectable' => $widgetIconSelectable,
            'widgetGraphicSelectable' => $widgetGraphicSelectable,
            'widgetSelected' => $widgetSelected,
            'providerIcon' => $providerIcon,
            'providerGraphic' => $providerGraphic,
            'currentView' => $this->getCurrentView(),
            'availableViews' => $this->getAvailableViews(),
            'model' => $this->getModelSearch()
        ];
        return $this->render('index', $params);
    }

    private function saveDashboardConfig()
    {

        $oldDashboard = ArrayHelper::map($this->getCurrentDashboard()->amosWidgetsClassnames, 'classname', 'classname');

        $newDashboard = Yii::$app->getRequest()->post('amosWidgetsClassnames');

        if (!count($newDashboard)) {
            Yii::$app->getSession()->addFlash('success', AmosDashboard::t('amosdashboard', 'Dashboard svuotata.'));
            $this->getCurrentDashboard()->unlinkAll('amosWidgetsClassnames', true);
            return true;
        }

        if (count($oldDashboard)) {
            foreach ($oldDashboard as $classname => $v) {
                if (!ArrayHelper::isIn($classname, $newDashboard)) {
                    $this->getCurrentDashboard()->unlink('amosWidgetsClassnames',
                        AmosWidgets::findOne(['classname' => $classname, 'sub_dashboard' => 0]), true);
                }
            }
        }

        foreach ($newDashboard as $k => $classname) {

            if (!ArrayHelper::isIn($classname, $oldDashboard)) {
                $widget = AmosWidgets::findOne(['classname' => $classname, 'sub_dashboard' => 0]);
                if (!empty($widget->id)) {
                    $this->getCurrentDashboard()->link('amosWidgetsClassnames', $widget,
                        [
                        'amos_widgets_id' => $widget->id,
                        'order' => $this->getCurrentDashboard()->getMaxOrder() + 1
                    ]);
                }
            }
        }

        return true;
    }

    /**
     * @param null $layout
     * @return bool
     */
    public function setUpLayout($layout = null)
    {
        if ($layout === false) {
            $this->layout = false;
            return true;
        }
        $this->layout = (!empty($layout)) ? $layout : $this->layout;
        $module = \Yii::$app->getModule('layout');
        if (empty($module)) {
            if (strpos($this->layout, '@') === false) {
                $this->layout = '@vendor/open20/amos-core/views/layouts/'.(!empty($layout) ? $layout : $this->layout);
            }
            return true;
        }
        return true;
    }
}