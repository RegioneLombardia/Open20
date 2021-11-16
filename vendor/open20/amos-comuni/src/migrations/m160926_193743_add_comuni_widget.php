<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comuni
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;

class m160926_193743_add_comuni_widget extends AmosMigrationWidgets
{
    const MODULE_NAME = 'comuni';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\comuni\widgets\icons\WidgetIconAmmComuni::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_DISABLED,
                'child_of' => NULL
            ],
            [
                'classname' => \open20\amos\comuni\widgets\icons\WidgetIconComuni::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_DISABLED,
                'child_of' => \open20\amos\comuni\widgets\icons\WidgetIconAmmComuni::className()
            ],
            [
                'classname' => \open20\amos\comuni\widgets\icons\WidgetIconProvince::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_DISABLED,
                'child_of' => \open20\amos\comuni\widgets\icons\WidgetIconAmmComuni::className(),
            ],
        ];
    }
}
