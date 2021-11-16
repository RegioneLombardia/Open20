<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\migrations
 * @category   CategoryName
 */

use yii\db\Migration;

/**
 * Class m210408_162300_sort_order_plugins
 */
class m210408_162300_sort_order_plugins extends \open20\amos\core\migration\AmosMigrationWidgets
{

    /**
     * @inheritdoc
     */
    public
    function afterAddWidgets()
    {
        return \open20\amos\dashboard\utility\DashboardUtility::resetAllDashboards();
    }

    /**
     * @inheritdoc
     */
    public
    function afterRemoveWidgets()
    {
        return \open20\amos\dashboard\utility\DashboardUtility::resetAllDashboards();
    }

    /**
     * @inheritdoc
     */
    protected
    function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \backend\modules\campains\widgets\icons\WidgetCampains::className(),
                'update' => true,
                'default_order' => 10
            ],
            [
                'classname' => \backend\modules\landings\widgets\icons\WidgetIconPreferenceLandings::className(),
                'update' => true,
                'default_order' => 20
            ],
            [
                'classname' => \backend\modules\operators\widgets\icons\WidgetOperators::className(),
                'update' => true,
                'default_order' => 30
            ],
            [
                'classname' => \backend\modules\registeredusers\widgets\icons\WidgetRegisteredUser::className(),
                'update' => true,
                'default_order' => 40
            ],
            [
                'classname' => \backend\modules\thematics\widgets\icons\WidgetThematic::className(),
                'update' => true,
                'default_order' => 50
            ],
            [
                'classname' => \backend\modules\statistics\widgets\icons\WidgetDataAnalysis::className(),
                'update' => true,
                'default_order' => 60
            ],
        ];
    }
}