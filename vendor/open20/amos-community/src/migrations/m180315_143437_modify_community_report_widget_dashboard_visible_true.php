<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;

/**
 * Class m180315_143437_modify_community_report_widget_dashboard_visible_true
 */
class m180315_143437_modify_community_report_widget_dashboard_visible_true extends AmosMigrationWidgets
{
    const MODULE_NAME = 'community';
    
    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\community\widgets\graphics\WidgetGraphicsCommunityReports::className(),
                'update' => true,
                'dashboard_visible' => 1
            ]
        ];
    }
}
