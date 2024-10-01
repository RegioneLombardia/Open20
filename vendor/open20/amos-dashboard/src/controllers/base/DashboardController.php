<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\dashboard
 * @category   CategoryName
 */

namespace open20\amos\dashboard\controllers\base;

use open20\amos\core\controllers\AmosController;
use open20\amos\dashboard\models\AmosUserDashboards;
use open20\amos\dashboard\models\search\AmosUserDashboardsSearch;
use open20\amos\dashboard\models\search\AmosWidgetsSearch;
use Yii;
use yii\db\ActiveQuery;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

/**
 * Class DashboardController
 * @package open20\amos\dashboard\controllers\base
 */
class DashboardController extends AmosController
{
    /**
     * @inheritdoc
     */
    public $layout = 'dashboard';

    /**
     * @var integer $slide
     */
    public $slide = 1;

    /**
     * @var $currentDashboard AmosUserDashboards
     */
    public $currentDashboard;

    /**
     * @var string $customModule This custom module is useful to generate other tab internal dashboard other than the base plugin unique name.
     */
    public $customModule;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setUpLayout();
        $AmosUserDashboardsSearch = new AmosUserDashboardsSearch();
        $params                   = [
            'user_id' => Yii::$app->getUser()->getId(),
            'slide' => $this->getSlide(),
            'module' => isset($_GET['module']) ? $_GET['module'] : (!is_null($this->customModule) ? $this->customModule : $this->module->getUniqueId())
        ];

        $Dashboard = $AmosUserDashboardsSearch->current($params)->one();

        if (!$Dashboard) {
            $Dashboard = new AmosUserDashboards($params);
            $Dashboard->save();
        }

        $this->setCurrentDashboard($Dashboard);

        if (Yii::$app->getModule('dashboard')->initIfEmpty) {
            $this->initEmptyDashboard();
        }

        if (Yii::$app->getModule('dashboard')->refreshWidgets) {
            if (!(!empty(\Yii::$app->params['performance']) && \Yii::$app->params['performance'] == true)) {
                $this->refreshDashboard();
                $this->updateDashboardDate();
            }
        }
    }

    /**
     *
     */
    protected function updateDashboardDate()
    {
        $CurrentDashboard             = $this->getCurrentDashboard();
        $CurrentDashboard->updated_at = date('Y-m-d H:i:s');
        $CurrentDashboard->save();
    }

    /**
     * @return int
     */
    public function getSlide()
    {
        return $this->slide;
    }

    /**
     * @param int $slide
     */
    public function setSlide($slide)
    {
        $this->slide = $slide;
    }

    /**
     * 
     */
    protected function initEmptyDashboard()
    {
        /** @var ActiveQuery $amosWidgetsQuery */
        $AmosWidgetsQuery = null;
        $CurrentDashboard = $this->getCurrentDashboard();

        if ($CurrentDashboard->getAmosWidgetsClassnames()->count() == 0) {
            $AmosWidgetsQuery = $this->getDashboardWidgets();
            $widgets          = $AmosWidgetsQuery->all();
            $this->setUserWidgets($widgets);
        }
    }

    /**
     *
     */
    protected function refreshDashboard()
    {
        /** @var ActiveQuery $amosWidgetsQuery */
        $AmosWidgetsQuery = null;
        $CurrentDashboard = $this->getCurrentDashboard();

        $AmosWidgetsQuery = $this->getDashboardWidgets();
        $widgets          = $AmosWidgetsQuery->all();
        $this->updateUserWidgets($widgets);
    }

    /**
     *
     * @return type
     */
    protected function getDashboardWidgets()
    {
        /** @var ActiveQuery $amosWidgetsQuery */
        $AmosWidgetsQuery = null;
        if (Yii::$app->getModule('dashboard')->initAllWidgets) {
            $AmosWidgetsQuery = $this->getAllWidgets();
        }

        if (Yii::$app->getModule('dashboard')->initHierarchyWidgets) {
            $AmosWidgetsQuery = $this->getHierarchyWidgets();
        } else if (Yii::$app->getModule('dashboard')->initChildWidget) {
            $AmosWidgetsQuery = $this->getChildWidget();
        }

        return $AmosWidgetsQuery;
    }

    /**
     *
     */
    protected function setUserWidgets($widgets)
    {
        $CurrentDashboard = $this->getCurrentDashboard();
        $orderGr          = [];
        $orderIc          = [];
        if (!empty($widgets) && is_array($widgets)) {
            foreach ($widgets as $widget) {
                if ($widget->type == \open20\amos\dashboard\models\AmosWidgets::TYPE_GRAPHIC) {
                    $widgetOrder = $this->getOrder($widget->default_order, $orderGr);
                    $orderGr[]   = $widgetOrder;
                } else {
                    $widgetOrder = $this->getOrder($widget->default_order, $orderIc);
                    $orderIc[]   = $widgetOrder;
                }

                $CurrentDashboard->link('amosWidgetsClassnames', $widget,
                    [
                    'amos_widgets_id' => $widget['id'],
                    'order' => $widgetOrder
                ]);
            }
        }
    }

    /**
     * 
     */
    protected function getMaxOrderWidget()
    {
        $widgetOrderMax = $this->getCurrentDashboard()->getAmosUserDashboardsWidgetMms()->orderBy('order DESC')->one();
        $order          = 0;
        if ($widgetOrderMax) {
            $order = $widgetOrderMax->order;
        }

        return $order;
    }

    /**
     * 
     */
    protected function updateUserWidgets($widgets)
    {
        $CurrentDashboard = $this->getCurrentDashboard();
        $currentWidgets   = $CurrentDashboard->amosUserDashboardsWidgetMms;
        if (!empty($widgets) && is_array($widgets)) {
            $order       = $this->getMaxOrderWidget() + 1;
            $userWidgets = [];
            foreach ($currentWidgets as $v) {
                $userWidgets[] = $v->amos_widgets_classname;
            }
            foreach ($widgets as $widget) {
                if ($widget->created_at > $CurrentDashboard->updated_at && !in_array($widget->classname, $userWidgets)) {
                    $CurrentDashboard->link('amosWidgetsClassnames', $widget,
                        [
                        'amos_widgets_id' => $widget->id,
                        'order' => $order,
                    ]);
                } else if (!$CurrentDashboard->isPrimary() && !in_array($widget->classname, $userWidgets)) {
                    $CurrentDashboard->link('amosWidgetsClassnames', $widget,
                        [
                        'amos_widgets_id' => $widget->id,
                        'order' => $order,
                    ]);
                }
            }
        }
    }

    /**
     * 
     */
    protected function getAllWidgets()
    {
        /** @var ActiveQuery $amosWidgetsQuery */
        $AmosWidgetsQuery = null;
        $CurrentDashboard = $this->getCurrentDashboard();
        if ($CurrentDashboard->isPrimary()) {

            $AmosWidgetsQuery = AmosWidgetsSearch::selectable();
        } else {

            $AmosWidgetsQuery = AmosWidgetsSearch::selectable()
                ->andWhere(
                [
                    'module' => $CurrentDashboard->module
                ]
            );
        }

        return $AmosWidgetsQuery;
    }

    /**
     *
     * @return type
     */
    protected function getHierarchyWidgets()
    {
        /** @var ActiveQuery $amosWidgetsQuery */
        $AmosWidgetsQuery = null;
        $CurrentDashboard = $this->getCurrentDashboard();
        if ($CurrentDashboard->isPrimary()) {

            $AmosWidgetsQuery = AmosWidgetsSearch::selectable()
                ->andWhere([
                'child_of' => null
            ]);
            $AmosWidgetsQuery->union(AmosWidgetsSearch::selectableGraphic());
        } else {

            $AmosWidgetsQuery = AmosWidgetsSearch::selectable()
                ->andWhere(
                    ['is not', 'child_of', null]
                )
                ->andWhere(
                [
                    'module' => $CurrentDashboard->module
                ]
            );
        }

        return $AmosWidgetsQuery;
    }

    /**
     *
     * @return type
     */
    protected function getChildWidget()
    {
        /** @var ActiveQuery $amosWidgetsQuery */
        $AmosWidgetsQuery = null;
        $CurrentDashboard = $this->getCurrentDashboard();
        if ($CurrentDashboard->isPrimary()) {
            $AmosWidgetsQuery = AmosWidgetsSearch::selectableIcon(0, null, true)
                ->andWhere(
                ['is not', 'child_of', null]
            );
            $AmosWidgetsQuery->union(AmosWidgetsSearch::selectableGraphic(0, null, true));
        } else {

            $AmosWidgetsQuery = AmosWidgetsSearch::selectable()
                ->andWhere(
                    ['is not', 'child_of', null]
                )
                ->andWhere(
                [
                    'module' => $CurrentDashboard->module
                ]
            );
        }
        return $AmosWidgetsQuery;
    }

    /**
     * @return AmosUserDashboards
     */
    public function getCurrentDashboard()
    {
        return $this->currentDashboard;
    }

    /**
     * @param AmosUserDashboards $currentDashboard
     */
    public function setCurrentDashboard($currentDashboard)
    {
        $this->currentDashboard = $currentDashboard;
    }

    /**
     *
     * @return type
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),
                [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'actions' => ['save-dashboard-order', 'index'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'logout' => ['post'],
                    ],
                ],
        ]);
    }

    /**
     * Get the initial order of the dashboard
     * @param integer $widget_order
     * @param array $order
     * @return array
     */
    protected function getOrder($widget_order, $order = [])
    {
        try {
            if (in_array($widget_order, $order)) {
                $widget_order = $this->getOrder($widget_order + 1, $order);
            }
            return $widget_order;
        } catch (\Exception $ex) {
            return [];
        }
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
        $module       = \Yii::$app->getModule('layout');
        if (empty($module)) {
            if (strpos($this->layout, '@') === false) {
                $this->layout = '@vendor/open20/amos-core/views/layouts/'.(!empty($layout) ? $layout : $this->layout);
            }
            // return true;
        }

        return true;
    }
}