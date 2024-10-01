<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;

/**
 * Class m180112_134937_change_widget_organizzazioni_dashboard_visible
 */
class m180112_134937_change_widget_organizzazioni_dashboard_visible extends AmosMigrationWidgets
{
    const MODULE_NAME = 'organizzazioni';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\organizzazioni\widgets\icons\WidgetIconProfilo::className(),
                'update' => true,
                'default_order' => 1,
                'dashboard_visible' => 1
            ]
        ];
    }
}
