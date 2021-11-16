<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\rss\migrations
 * @category   CategoryName
 */

use amos\rss\widgets\graphics\WidgetGraphicsRssNewsCategory;
use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;

/**
 * Class m181211_094647_create_widgets_rss_news_category
 */
class m181211_094647_create_widgets_rss_news_category extends AmosMigrationWidgets
{
    const MODULE_NAME = 'rss';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => WidgetGraphicsRssNewsCategory::className(),
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => null,
                'dashboard_visible' => 1,
                'default_order' => 1
            ]
        ];
    }
}
