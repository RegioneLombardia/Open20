<?php

use open20\amos\dashboard\utility\DashboardUtility;
use open20\amos\core\migration\AmosMigrationWidgets;

/**
 * Handles the creation of table `m210701_114013_dashboard_visible_widgets6`.
 */
class m210701_114013_dashboard_visible_widgets6 extends AmosMigrationWidgets
{
    /**
     * @inheritdoc
     */
    public function afterAddWidgets()
    {
        return DashboardUtility::resetAllDashboards();
    }

    /**
     * @inheritdoc
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
                'classname' => \open20\amos\sondaggi\widgets\icons\WidgetIconSondaggiGeneral::className(),
                'update' => true,
                'module' => 'sondaggi',
                'dashboard_visible' => 1,
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\sondaggi\widgets\icons\WidgetIconSondaggiGeneral::className(),
                'update' => true,
                'module' => 'sondaggi',
                'dashboard_visible' => 0,
            ]
        ];
        foreach ($this->widgets as $widgetData) {
            $ok = $this->insertOrUpdateWidget($widgetData);
            if (!$ok) {
                $allOk = false;
            }
        }
        return $allOk;
    }
}


