<?php

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;
use open20\amos\dashboard\utility\DashboardUtility;

class m201023_154900_add_widgets_podcast extends AmosMigrationWidgets
{
    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \amos\podcast\widgets\icons\WidgetIconDashboardPodcast::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => 'podcast',
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => null,
                'default_order' => 200,
                'dashboard_visible' => 1,
            ],
            [
                'classname' => \amos\podcast\widgets\icons\WidgetIconPodcastOwn::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => 'podcast',
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \amos\podcast\widgets\icons\WidgetIconDashboardPodcast::className(),
                'default_order' => 10,
                'dashboard_visible' => 0,
            ],
            [
                'classname' => \amos\podcast\widgets\icons\WidgetIconPodcastPublished::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => 'podcast',
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \amos\podcast\widgets\icons\WidgetIconDashboardPodcast::className(),
                'default_order' => 20,
                'dashboard_visible' => 0,
            ],
            [
                'classname' => \amos\podcast\widgets\icons\WidgetIconPodcastToValidate::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => 'podcast',
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \amos\podcast\widgets\icons\WidgetIconDashboardPodcast::className(),
                'default_order' => 30,
                'dashboard_visible' => 0,
            ],
            [
                'classname' => \amos\podcast\widgets\icons\WidgetIconPodcastAdmin::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => 'podcast',
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \amos\podcast\widgets\icons\WidgetIconDashboardPodcast::className(),
                'default_order' => 40,
                'dashboard_visible' => 0,
            ],
            [
                'classname' => \amos\podcast\widgets\icons\WidgetIconPodcastCategory::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => 'podcast',
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \amos\podcast\widgets\icons\WidgetIconDashboardPodcast::className(),
                'default_order' => 50,
                'dashboard_visible' => 0,
            ],

        ];

        DashboardUtility::resetDashboardsByModule("podcast");
    }
}
