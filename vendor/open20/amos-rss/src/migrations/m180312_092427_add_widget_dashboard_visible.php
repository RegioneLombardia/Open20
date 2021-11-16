<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\rss\migrations
 * @category   CategoryName
 */

use amos\rss\widgets\graphics\WidgetGraphicsRssUltimeNews;
use open20\amos\core\migration\AmosMigrationWidgets;

/**
 * Class m180312_092427_add_widget_dashboard_visible
 */
class m180312_092427_add_widget_dashboard_visible extends AmosMigrationWidgets
{
    const MODULE_NAME = 'rss';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => WidgetGraphicsRssUltimeNews::className(),
                'dashboard_visible' => 1,
                'update' => true
            ]
        ];
    }
}
