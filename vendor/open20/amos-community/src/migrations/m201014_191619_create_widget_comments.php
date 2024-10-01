<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\dashboard\models\AmosWidgets;
use open20\amos\core\migration\AmosMigrationWidgets;

/**
 * Class m201014_191619_create_widget_comments
 */
class m201014_191619_create_widget_comments extends AmosMigrationWidgets
{
    const MODULE_NAME = 'community';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\community\widgets\graphics\WidgetGraphicsComments::className(),
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 0,
                'child_of' => \open20\amos\community\widgets\icons\WidgetIconCommunityDashboard::className(),
                'default_order' => 1
            ],
        ];
    }
}
