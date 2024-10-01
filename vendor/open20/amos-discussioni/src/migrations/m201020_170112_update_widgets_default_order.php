<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *

 */

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\utility\DashboardUtility;

/**
 * Class m201020_170112_update_widgets_default_order
 */
class m201020_170112_update_widgets_default_order extends AmosMigrationWidgets
{
    /**
     * Override this to make operations after adding the widgets.
     * @return bool
     */
    public function afterAddWidgets()
    {
        return DashboardUtility::resetAllDashboards();
    }

    /**
     * Override this to make operations after removing the widgets.
     * @return bool
     */
    public function afterRemoveWidgets()
    {
        return DashboardUtility::resetAllDashboards();
    }

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopicAll::className(),
                'update' => true,
                'default_order' => 100
            ],
            [
                'classname' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopic::className(),
                'update' => true,
                'default_order' => 110
            ],
            [
                'classname' => open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopicCreatedBy::className(),
                'update' => true,
                'default_order' => 120
            ],
            [
                'classname' => open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopicDaValidare::className(),
                'update' => true,
                'default_order' => 130
            ],
            [
                'classname' => open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopicAdminAll::className(),
                'update' => true,
                'default_order' => 140
            ],
           
           
            
        ];
    }
}
