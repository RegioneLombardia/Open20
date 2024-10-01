<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni
 * @category   CategoryName
 */

use yii\db\Migration;
use open20\amos\dashboard\models\AmosWidgets;

class m171208_123616_create_widgets extends \open20\amos\core\migration\AmosMigrationWidgets
{
    const MODULE_NAME = 'organizzazioni';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\organizzazioni\widgets\icons\WidgetIconProfilo::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED
            ]
        ];
    }
}
