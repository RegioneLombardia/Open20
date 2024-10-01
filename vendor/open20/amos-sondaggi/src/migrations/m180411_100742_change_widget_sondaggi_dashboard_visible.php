<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;

/**
 * Class m180411_100742_change_widget_sondaggi_dashboard_visible
 */
class m180411_100742_change_widget_sondaggi_dashboard_visible extends AmosMigrationWidgets
{
    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\sondaggi\widgets\icons\WidgetIconCompilaSondaggi::className(),
                'update' => true,
                'default_order' => 110,
                'dashboard_visible' => 1
            ],
            [
                'classname' => \open20\amos\sondaggi\widgets\icons\WidgetIconPubblicaSondaggi::className(),
                'update' => true,
                'default_order' => 120,
                'dashboard_visible' => 1
            ],
            [
                'classname' => \open20\amos\sondaggi\widgets\icons\WidgetIconSondaggi::className(),
                'update' => true,
                'default_order' => 130,
                'dashboard_visible' => 1
            ]
        ];
    }
}
