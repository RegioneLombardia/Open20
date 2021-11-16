<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;

/**
 * Class m170315_135851_init_events_widgets
 */
class m170315_135851_init_events_widgets extends AmosMigrationWidgets
{
    const MODULE_NAME = 'events';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\events\widgets\icons\WidgetIconEvents::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\events\widgets\icons\WidgetIconEvents::className(),
                'default_order' => 10
            ],
            [
                'classname' => \open20\amos\events\widgets\icons\WidgetIconEventsCreatedBy::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\events\widgets\icons\WidgetIconEvents::className(),
                'default_order' => 20
            ],
            [
                'classname' => \open20\amos\events\widgets\icons\WidgetIconEventTypes::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\events\widgets\icons\WidgetIconEvents::className(),
                'default_order' => 30
            ],
            [
                'classname' => \open20\amos\events\widgets\icons\WidgetIconEventsToPublish::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\events\widgets\icons\WidgetIconEvents::className(),
                'default_order' => 40
            ],
            [
                'classname' => \open20\amos\events\widgets\icons\WidgetIconEventsManagement::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\events\widgets\icons\WidgetIconEvents::className(),
                'default_order' => 50
            ]
        ];
    }
}
