<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\utility\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;
use open20\amos\dashboard\widgets\icons\WidgetIconManagement;
use open20\amos\utility\widgets\icons\WidgetIconUtility;

/**
 * Class m191127_102711_init_utility_widget
 */
class m191127_102711_init_utility_widget extends AmosMigrationWidgets
{
    const MODULE_NAME = 'utility';

    /**
     * {@inheritdoc}
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => WidgetIconUtility::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => WidgetIconManagement::className(),
                'dashboard_visible' => 1,
                'default_order' => 100
            ]
        ];
    }
}
