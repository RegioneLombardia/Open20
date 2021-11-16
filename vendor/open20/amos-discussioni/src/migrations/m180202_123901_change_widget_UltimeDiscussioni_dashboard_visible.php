<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;

/**
 * Class m180202_123901_change_widget_UltimeDiscussioni_dashboard_visible
 */
class m180202_123901_change_widget_UltimeDiscussioni_dashboard_visible extends AmosMigrationWidgets
{
    const MODULE_NAME = 'discussioni';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => open20\amos\discussioni\widgets\graphics\WidgetGraphicsUltimeDiscussioni::className(),
                'dashboard_visible' => 1,
                'update' => true
            ]
        ];
    }
}
