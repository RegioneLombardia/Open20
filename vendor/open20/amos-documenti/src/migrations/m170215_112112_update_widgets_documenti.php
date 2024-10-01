<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;

/**
 * Class m170215_112112_update_widgets_documenti
 */
class m170215_112112_update_widgets_documenti extends AmosMigrationWidgets
{
    const MODULE_NAME = 'documenti';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = array_merge(
            $this->initIconWidgetsConf(),
            $this->initGraphicWidgetsConf()
        );
    }

    /**
     * Init the icon widgets configurations
     * @return array
     */
    private function initIconWidgetsConf()
    {
        return [
            [
                'classname' => open20\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'default_order' => 1,
                'update' => true
            ],
            [
                'classname' => \open20\amos\documenti\widgets\icons\WidgetIconDocumenti::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard::className(),
                'default_order' => 10,
                'update' => true
            ],
            [
                'classname' => open20\amos\documenti\widgets\icons\WidgetIconDocumentiCreatedBy::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard::className(),
                'default_order' => 30,
                'update' => true
            ],
            [
                'classname' => \open20\amos\documenti\widgets\icons\WidgetIconAllDocumenti::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard::className(),
                'default_order' => 40,
                'update' => true
            ],
            [
                'classname' => open20\amos\documenti\widgets\icons\WidgetIconDocumentiDaValidare::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard::className(),
                'default_order' => 50,
                'update' => true
            ],
            [
                'classname' => open20\amos\documenti\widgets\icons\WidgetIconDocumentiCategorie::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard::className(),
                'default_order' => 60,
                'update' => true
            ]
        ];
    }

    /**
     * Init the graphic widgets configurations
     * @return array
     */
    private function initGraphicWidgetsConf()
    {
        return [
            [
                'classname' => \open20\amos\documenti\widgets\graphics\WidgetGraphicsUltimiDocumenti::className(),
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard::className(),
                'default_order' => 1,
                'update' => true
            ]
        ];
    }
}
