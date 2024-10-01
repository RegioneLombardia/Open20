<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;
use open20\amos\dashboard\utility\DashboardUtility;

/**
 * Class m180306_154901_add_site_management_metadata_widget
 */
class m180306_154901_add_site_management_metadata_widget extends AmosMigrationWidgets
{
    const MODULE_NAME = 'sitemanagement';

    /**
     * @inheritdoc
     */
    public function afterAddWidgets()
    {
        return DashboardUtility::resetDashboardsByModule(self::MODULE_NAME);
    }

    /**
     * Override this to make operations after removing the widgets.
     * @return bool
     */
    public function afterRemoveWidgets()
    {
        return DashboardUtility::resetDashboardsByModule(self::MODULE_NAME);
    }

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementMetadata::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementDashboard::className(),
                'dashboard_visible' => 0,
                'default_order' => 20
            ]
        ];
    }
}
