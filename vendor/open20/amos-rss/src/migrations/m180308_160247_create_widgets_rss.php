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
use open20\amos\dashboard\models\AmosWidgets;

/**
 * Class m180308_160247_create_widgets_rss
 */
class m180308_160247_create_widgets_rss extends AmosMigrationWidgets
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
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
            ]
        ];
    }
}
