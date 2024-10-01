<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\slideshow
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;

class m161028_130926_add_slideshow_widgets extends AmosMigrationWidgets
{
    const MODULE_NAME = 'slideshow';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\slideshow\widgets\icons\WidgetIconSlideshow::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED
            ],
            [
                'classname' => \open20\amos\slideshow\widgets\icons\WidgetIconSlideshowConf::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'child_of' => open20\amos\slideshow\widgets\icons\WidgetIconSlideshow::className(),
                'status' => AmosWidgets::STATUS_ENABLED
            ],
        ];
    }
}