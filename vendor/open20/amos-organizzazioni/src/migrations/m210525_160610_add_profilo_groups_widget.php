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
use open20\amos\organizzazioni\widgets\icons\WidgetIconProfiloGroups;

/**
 * Class m210525_160610_add_profilo_groups_widget
 */
class m210525_160610_add_profilo_groups_widget extends AmosMigrationWidgets
{
    const MODULE_NAME = 'organizzazioni';
    
    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => WidgetIconProfiloGroups::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'default_order' => 10,
                'dashboard_visible' => 1
            ]
        ];
    }
}
