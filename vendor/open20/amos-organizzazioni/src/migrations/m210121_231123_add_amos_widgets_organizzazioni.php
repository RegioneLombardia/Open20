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
use open20\amos\dashboard\models\AmosWidgets;

/**
 * Class m210121_231123_add_amos_widgets_organizzazioni
 */
class m210121_231123_add_amos_widgets_organizzazioni extends AmosMigrationWidgets
{
    const MODULE_NAME = 'organizzazioni';
    
    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => 'open20\amos\organizzazioni\widgets\icons\WidgetIconProfiloDashboard',
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => null,
                'dashboard_visible' => 1,
                'default_order' => 1
            ],
            [
                'classname' => \open20\amos\organizzazioni\widgets\icons\WidgetIconProfiloToValidate::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 0,
                'default_order' => 1,
                'child_of' => 'open20\amos\organizzazioni\widgets\icons\WidgetIconProfiloDashboard',
            ],
        ];
    }
}
