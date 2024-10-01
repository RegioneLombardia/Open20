<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\slideshow\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;

/**
 * Class m180316_094728_change_widget_slideshow_dashboard_visible
 */
class m180316_094728_change_widget_slideshow_dashboard_visible extends AmosMigrationWidgets
{
    const MODULE_NAME = 'documenti';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\slideshow\widgets\icons\WidgetIconSlideshowConf::className(),
                'dashboard_visible' => 1,
                'update' => true
            ]
        ];
    }
}
