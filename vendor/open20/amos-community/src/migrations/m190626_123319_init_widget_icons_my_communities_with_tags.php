<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\dashboard\models\AmosWidgets;
use open20\amos\core\migration\AmosMigrationWidgets;

/**
 * Class m190626_123319_init_widget_icons_my_communities_with_tags
 */
class m190626_123319_init_widget_icons_my_communities_with_tags extends AmosMigrationWidgets
{
    const MODULE_NAME = 'community';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\community\widgets\icons\WidgetIconMyCommunitiesWithTags::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 0,
                'child_of' => \open20\amos\community\widgets\icons\WidgetIconCommunityDashboard::className(),
                'default_order' => 15
            ],
        ];
    }
}
