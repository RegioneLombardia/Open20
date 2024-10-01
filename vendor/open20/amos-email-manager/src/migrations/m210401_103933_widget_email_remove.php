<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\email
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;
use open20\amos\emailmanager\widgets\icons\WidgetIconViews;

class m210401_103933_widget_email_remove extends AmosMigrationWidgets
{
    const MODULE_NAME = 'email';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => open20\amos\emailmanager\widgets\icons\WidgetIconEmailManager::className(),
                'status' => AmosWidgets::STATUS_DISABLED,
                'update' => true
            ]
        ];
    }

    
}
