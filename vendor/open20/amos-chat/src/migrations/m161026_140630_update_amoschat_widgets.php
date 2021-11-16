<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\chat
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;

/**
 * Class m161026_140630_update_amoschat_widgets
 */
class m161026_140630_update_amoschat_widgets extends AmosMigrationWidgets
{
    const MODULE_NAME = 'chat';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\chat\widgets\icons\WidgetIconChat::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\chat\widgets\icons\WidgetIconChat::className(),
                'update' => true
            ]
        ];
    }
}
