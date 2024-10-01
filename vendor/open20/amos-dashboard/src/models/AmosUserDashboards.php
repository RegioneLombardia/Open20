<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\dashboard
 * @category   CategoryName
 */

namespace open20\amos\dashboard\models;

use yii\db\Exception;
use yii\db\Query;

/**
 * This is the model class for table "amos_user_dashboards".
 *
 * @property AmosWidgets[] $amosWidgetsSelectedIcon
 * @property AmosWidgets[] $amosWidgetsSelectedGraphic
 */
class AmosUserDashboards extends \open20\amos\dashboard\models\base\AmosUserDashboards
{

    public $showAllModule = false;

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAmosWidgetsSelectedIcon($forceAll = false, $widgetParentClassname = null)
    {
        $relQuery = $this->getWidgetBaseQuery()
            ->andWhere(['amos_widgets.type' => AmosWidgets::TYPE_ICON])
            ->andFilterWhere(['child_of'=> $widgetParentClassname]);
        if ($forceAll == false) {
            $relQuery = $relQuery->andWhere(['dashboard_visible' => 1]);
        }
        return $relQuery;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    protected function getWidgetBaseQuery()
    {
        $relWidgetBaseQuery = $this->getAmosWidgetsClassnames($this->showAllModule)
            ->andWhere(['amos_widgets.status' => AmosWidgets::STATUS_ENABLED]);

        return $relWidgetBaseQuery;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAmosWidgetsSelectedGraphic()
    {
        $relQuery = $this->getWidgetBaseQuery()
            ->andWhere(['amos_widgets.type' => AmosWidgets::TYPE_GRAPHIC]);
        if (\Yii::$app->getModule('dashboard')->useWidgetGraphicDashboardVisible == true) {
            $relQuery = $relQuery->andWhere(['dashboard_visible' => 1]);
        }
        if (\Yii::$app->getModule('dashboard')->useWidgetGraphicOrder == true) {
            $relQuery->orderBy('amos_widgets.default_order');
        }
        return $relQuery;
    }

    /**
     * @return bool
     */
    public function isPrimary()
    {
        return $this->slide == 1 && $this->module == 'dashboard';
    }

    /**
     * @return int
     */
    public function getMaxOrder()
    {
        $subquery = new Query();
        return $subquery->from(['subquery' => $this->getAmosWidgetsClassnames()])->max('widget_order');
    }
}