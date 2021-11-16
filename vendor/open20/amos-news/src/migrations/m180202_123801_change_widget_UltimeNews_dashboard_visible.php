<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;

/**
 * Class m180202_123801_change_widget_UltimeNews_dashboard_visible
 */
class m180202_123801_change_widget_UltimeNews_dashboard_visible extends AmosMigrationWidgets
{
    const MODULE_NAME = 'news';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => open20\amos\news\widgets\graphics\WidgetGraphicsUltimeNews::className(),
                'dashboard_visible' => 1,
                'update' => true
            ]
        ];
    }
}
