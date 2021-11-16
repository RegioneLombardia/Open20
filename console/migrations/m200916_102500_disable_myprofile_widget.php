<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    preference\platform\common\console\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;

/**
 * Class m200916_102500_disable_myprofile_widget
 */
class m200916_102500_disable_myprofile_widget extends AmosMigrationWidgets
{
    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\admin\widgets\graphics\WidgetGraphicMyProfile::className(),
                'update' => true,
                'dashboard_visible' => 0,
                'sub_dashboard' => 0,
            ],
            [
                'classname' => \open20\amos\admin\widgets\icons\WidgetIconAdmin::className(),
                'update' => true,
                'dashboard_visible' => 0,
                'sub_dashboard' => 0,
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\admin\widgets\graphics\WidgetGraphicMyProfile::className(),
                'update' => true,
                'dashboard_visible' => 1,
                'sub_dashboard' => 1,
            ],
            [
                'classname' => \open20\amos\admin\widgets\icons\WidgetIconAdmin::className(),
                'update' => true,
                'dashboard_visible' => 1,
                'sub_dashboard' => 1,
            ]
        ];

        $allOk = true;
        foreach ($this->widgets as $widgetData) {
            $ok = $this->insertOrUpdateWidget($widgetData);
            if (!$ok) {
                $allOk = false;
            }
        }

        return $allOk;
    }
}
