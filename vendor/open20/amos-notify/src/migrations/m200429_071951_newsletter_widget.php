<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notify\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;
use open20\amos\notificationmanager\widgets\icons\WidgetIconNewsletterAll;
use open20\amos\notificationmanager\widgets\icons\WidgetIconNewsletterCreatedBy;
use open20\amos\notificationmanager\widgets\icons\WidgetIconNewsletterDashboard;

/**
 * Class m200429_071951_newsletter_widget
 */
class m200429_071951_newsletter_widget extends AmosMigrationWidgets
{
    const MODULE_NAME = 'notify';
    
    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => WidgetIconNewsletterDashboard::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'default_order' => 120,
                'dashboard_visible' => 1
            ],
            [
                'classname' => WidgetIconNewsletterAll::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => WidgetIconNewsletterDashboard::className(),
                'default_order' => 10,
                'dashboard_visible' => 0
            ],
            [
                'classname' => WidgetIconNewsletterCreatedBy::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => WidgetIconNewsletterDashboard::className(),
                'default_order' => 20,
                'dashboard_visible' => 0
            ]
        ];
    }
}
