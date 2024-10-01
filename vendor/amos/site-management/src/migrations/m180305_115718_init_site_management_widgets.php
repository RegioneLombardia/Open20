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

/**
 * Class m180305_115718_init_site_management_widgets
 */
class m180305_115718_init_site_management_widgets extends AmosMigrationWidgets
{
    const MODULE_NAME = 'sitemanagement';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementDashboard::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => null,
                'dashboard_visible' => 1,
                'default_order' => 1
            ],
            [
                'classname' => \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementPageContent::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementDashboard::className(),
                'dashboard_visible' => 0,
                'default_order' => 10
            ]
        ];
    }
}
