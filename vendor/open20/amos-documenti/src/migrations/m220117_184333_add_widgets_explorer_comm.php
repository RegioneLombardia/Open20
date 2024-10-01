<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;

class m220117_184333_add_widgets_explorer_comm extends AmosMigrationWidgets
{
    const MODULE_NAME = 'documenti';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\documenti\widgets\graphics\WidgetGraphicsHierarchicalDocumentsBefeCommunity::className(),
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 0,
            ],
            [
                'classname' => \open20\amos\documenti\widgets\graphics\WidgetGraphicsHierarchicalDocumentsBefeCommunity::className(),
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => 'community',
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 0,
                'sub_dashboard' => 0,
            ]
        ];
    }
}
