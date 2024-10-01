<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;

/**
 * Class m180411_093139_change_sondaggi_administration_widget
 */
class m180411_093139_change_sondaggi_administration_widget extends AmosMigrationWidgets
{
    const MODULE_NAME = 'sondaggi';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\sondaggi\widgets\icons\WidgetIconSondaggiAdministration::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'default_order' => 100,
                'dashboard_visible' => 1
            ],
            [
                'classname' => 'open20\amos\sondaggi\widgets\icons\WidgetIconSondaggiAdministration',
                'update' => true,
                'status' => AmosWidgets::STATUS_DISABLED
            ]
        ];
    }
}
