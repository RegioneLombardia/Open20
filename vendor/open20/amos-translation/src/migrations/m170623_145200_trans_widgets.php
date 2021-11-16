<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\translation\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;

/**
 * Class m170623_145200_trans_widgets
 */
class m170623_145200_trans_widgets extends AmosMigrationWidgets
{
    const MODULE_NAME = 'translation';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\translation\widgets\icons\WidgetIconTranslation::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => null,
                'default_order' => 10
            ],
            [
                'classname' => \open20\amos\translation\widgets\icons\WidgetIconTrContents::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\translation\widgets\icons\WidgetIconTranslation::className(),
                'default_order' => 20
            ],
            [
                'classname' => \open20\amos\translation\widgets\icons\WidgetIconTrPlatform::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\translation\widgets\icons\WidgetIconTranslation::className(),
                'default_order' => 30
            ],
            [
                'classname' => \open20\amos\translation\widgets\icons\WidgetIconTrLanguage::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\translation\widgets\icons\WidgetIconTranslation::className(),
                'default_order' => 40
            ],
            [
                'classname' => \open20\amos\translation\widgets\icons\WidgetIconTrOptimize::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\translation\widgets\icons\WidgetIconTranslation::className(),
                'default_order' => 50
            ],
            [
                'classname' => \open20\amos\translation\widgets\icons\WidgetIconTrScan::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\translation\widgets\icons\WidgetIconTranslation::className(),
                'default_order' => 60
            ],
        ];
    }
}
