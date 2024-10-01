<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;

/**
 * Class m180410_095645_add_events_community_widgets
 */
class m190614_155645_add_organizzazioni_community_widgets extends AmosMigrationWidgets
{
    const MODULE_NAME = 'organizzazioni';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => 'open20\amos\news\widgets\icons\WidgetIconNewsDashboard',
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'default_order' => 10,
                'dashboard_visible' => 0,
                'sub_dashboard' => 1
            ],
            [
                'classname' => 'open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniDashboard',
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'default_order' => 20,
                'dashboard_visible' => 0,
                'sub_dashboard' => 1
            ],
            [
                'classname' => 'open20\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard',
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'default_order' => 30,
                'dashboard_visible' => 0,
                'sub_dashboard' => 1
            ]
        ];
    }
}
