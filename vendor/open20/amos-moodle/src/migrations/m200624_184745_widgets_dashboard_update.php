<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    retecomuni\platform\prod\common\console\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;


/**
 * Class m200624_184745_widgets_dashboard_update
 */
class m200624_184745_widgets_dashboard_update extends AmosMigrationWidgets {


    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\moodle\widgets\icons\WidgetIconMoodle::className(),
                'update' => true,
                'dashboard_visible' => 0,
                'default_order' => 21,
                'child_of' => \open20\amos\moodle\widgets\icons\WidgetIconMoodleDashboard::className(),
            ],
            [
                'classname' => \open20\amos\moodle\widgets\icons\WidgetIconMoodleUserCourses::className(),
                'update' => true,
                'default_order' => 22,
            ],
            [
                'classname' => \open20\amos\moodle\widgets\icons\WidgetIconMoodleDashboard::className(),
                'update' => true,
                'default_order' => 20,
            ],

        ];
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\moodle\widgets\icons\WidgetIconMoodle::className(),
                'update' => true,
                'dashboard_visible' => 1,
                'child_of' => null
            ],
            
        ];
        foreach ($this->widgets as $widgetData) {
            $ok = $this->insertOrUpdateWidget($widgetData);
            if (!$ok) {
                $allOk = false;
            }
        }
        return $allOk;
    }
}