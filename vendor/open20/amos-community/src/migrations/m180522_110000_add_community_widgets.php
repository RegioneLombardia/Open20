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
 * Class m180410_095645_add_events_community_widgets
 */
class m180522_110000_add_community_widgets extends AmosMigrationWidgets
{
    const EVENTS_MODULE_NAME = 'community';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => 'open20\amos\documenti\widgets\graphics\WidgetGraphicsUltimiDocumenti',
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => self::EVENTS_MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'default_order' => 40,
                'dashboard_visible' => 0,
                'sub_dashboard' => 1
            ],
            [
                'classname' => 'open20\amos\community\widgets\graphics\WidgetGraphicsMyCommunities',
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => self::EVENTS_MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'default_order' => 30,
                'dashboard_visible' => 0,
                'sub_dashboard' => 1
            ],
            [
                'classname' => 'open20\amos\discussioni\widgets\graphics\WidgetGraphicsUltimeDiscussioni',
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => self::EVENTS_MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'default_order' => 20,
                'dashboard_visible' => 0,
                'sub_dashboard' => 1
            ],
            [
                'classname' => 'open20\amos\news\widgets\graphics\WidgetGraphicsUltimeNews',
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => self::EVENTS_MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'default_order' => 10,
                'dashboard_visible' => 0,
                'sub_dashboard' => 1
            ],
        ];
    }
}
