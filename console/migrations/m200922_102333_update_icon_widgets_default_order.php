<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    cruscotto-lavoro\platform\common\console\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\utility\DashboardUtility;

/**
 * Class m200922_102333_update_icon_widgets_default_order
 */
class m200922_102333_update_icon_widgets_default_order extends AmosMigrationWidgets
{

    /**
     * Override this to make operations after adding the widgets.
     * @return bool
     */
    public function afterAddWidgets()
    {
        return DashboardUtility::resetAllDashboards();
    }

    /**
     * Override this to make operations after removing the widgets.
     * @return bool
     */
    public function afterRemoveWidgets()
    {
        return DashboardUtility::resetAllDashboards();
    }

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => backend\modules\campains\widgets\icons\WidgetCampains::className(),
                'update' => true,
                'default_order' => 10
            ],
            [
                'classname' => backend\modules\landings\widgets\icons\WidgetIconPreferenceLandings::className(),
                'update' => true,
                'default_order' => 20
            ],
            [
                'classname' => backend\modules\operators\widgets\icons\WidgetOperators::className(),
                'update' => true,
                'default_order' => 30
            ],
            [
                'classname' => backend\modules\registeredusers\widgets\icons\WidgetRegisteredUser::className(),
                'update' => true,
                'default_order' => 40
            ],
            [
                'classname' => backend\modules\thematics\widgets\icons\WidgetThematic::className(),
                'update' => true,
                'default_order' => 50
            ],
        ];
    }

}