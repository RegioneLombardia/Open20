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
 * Class m190104_100401_widgt_clean_cache
 */
class m190104_100401_widgt_clean_cache extends AmosMigrationWidgets
{
    const MODULE_NAME = 'translation';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\translation\widgets\icons\WidgetIconTrCleanCache::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 0,
                'child_of' => \open20\amos\translation\widgets\icons\WidgetIconTranslation::className(),
                'default_order' => 100
            ],
        ];
    }
}
