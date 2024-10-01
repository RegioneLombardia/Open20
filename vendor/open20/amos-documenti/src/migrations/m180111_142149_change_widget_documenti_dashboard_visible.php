<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;

/**
 * Class m180111_142149_change_widget_documenti_dashboard_visible
 */
class m180111_142149_change_widget_documenti_dashboard_visible extends AmosMigrationWidgets
{
    const MODULE_NAME = 'documenti';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard::className(),
                'dashboard_visible' => 1,
                'update' => true
            ]
        ];
    }
}
