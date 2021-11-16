<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;

/**
 * Class m180131_135306_widget_community_dashboard_visible
 */
class m180131_135306_widget_community_dashboard_visible extends AmosMigrationWidgets
{
    const MODULE_NAME = 'community';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\community\widgets\icons\WidgetIconCommunityDashboard::className(),
                'dashboard_visible' => 1,
                'update' => true
            ]
        ];
    }
}
