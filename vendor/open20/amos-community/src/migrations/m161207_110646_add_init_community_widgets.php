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
use open20\amos\dashboard\models\AmosWidgets;

/**
 * Class m161207_110646_add_init_community_widgets
 */
class m161207_110646_add_init_community_widgets extends AmosMigrationWidgets
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
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED
            ],
            [
                'classname' => 'open20\amos\community\widgets\icons\WidgetIconTipologiaCommunity',
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\community\widgets\icons\WidgetIconCommunityDashboard::className(),
                'update' => true
            ],
            [
                'classname' => \open20\amos\community\widgets\icons\WidgetIconCommunity::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\community\widgets\icons\WidgetIconCommunityDashboard::className(),
                'update' => true
            ],
            [
                'classname' => \open20\amos\community\widgets\icons\WidgetIconMyCommunities::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\community\widgets\icons\WidgetIconCommunityDashboard::className(),
                'update' => true
            ],
            [
                'classname' => \open20\amos\community\widgets\icons\WidgetIconCreatedByCommunities::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\community\widgets\icons\WidgetIconCommunityDashboard::className(),
                'update' => true
            ],
        ];
    }
}
