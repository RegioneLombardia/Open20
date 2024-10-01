<?php
use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;

class m191204_102507_add_tokens_widget extends AmosMigrationWidgets
{
    const MODULE_NAME = 'socialauth';
    
    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\socialauth\widgets\icons\WidgetIconManageTokens::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'default_order' => 1,
                'dashboard_visible' => true,
                'update' => false,
                'dontRemove' => true
            ],
        ];
    }
}
