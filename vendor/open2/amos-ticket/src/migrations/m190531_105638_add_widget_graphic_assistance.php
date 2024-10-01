<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;

/**
 * Class m190531_105638_add_widget_graphic_assistance
 */
class m190531_105638_add_widget_graphic_assistance extends AmosMigrationWidgets
{
    const MODULE_NAME = 'ticket';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => open2\amos\ticket\widgets\graphics\WidgetGraphicAssistance::className(),
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'default_order' => 70,
                'dashboard_visible' => 1,
            ],
        ];
    }
}
