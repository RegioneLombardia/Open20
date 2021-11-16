<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;

/**
 * Class m190402_100208_restore_widget_graphic_my_profile
 */
class m190402_100208_restore_widget_graphic_my_profile extends AmosMigrationWidgets
{
    const MODULE_NAME = 'admin';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\admin\widgets\graphics\WidgetGraphicMyProfile::className(),
                'update' => true,
                'status' => AmosWidgets::STATUS_ENABLED,
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
                'status' => AmosWidgets::STATUS_DISABLED,
            ]
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
