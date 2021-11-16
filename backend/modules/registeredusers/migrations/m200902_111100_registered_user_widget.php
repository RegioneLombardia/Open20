<?php

use backend\modules\registeredusers\widgets\icons\WidgetRegisteredUser;
use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;


/**
* Class m200902_111100_registered_user_widget */
class m200902_111100_registered_user_widget extends AmosMigrationWidgets
{
    const MODULE_NAME = 'registeredusers';

    /**
    * @inheritdoc
    */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => WidgetRegisteredUser::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 1,
                'default_order' => 10,
                'child_of' =>  null,
            ]
        ];
    }
}
