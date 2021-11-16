<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\myactivities\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;

/**
 * Class m170712_141846_init_my_activities_widgets
 */
class m170718_141846_init_my_activities_widgets extends AmosMigrationWidgets
{
    const MODULE_NAME = 'myactivities';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\myactivities\widgets\icons\WidgetIconMyActivities::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'default_order' => 10,
                'dashboard_visible' => 1,
            ],
        ];
    }
}
