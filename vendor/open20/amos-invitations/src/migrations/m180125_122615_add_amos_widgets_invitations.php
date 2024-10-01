<?php
use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;


/**
 * Class m180125_122615_add_amos_widgets_invitations*/
class m180125_122615_add_amos_widgets_invitations extends AmosMigrationWidgets
{
    const MODULE_NAME = 'invitations';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\invitations\widgets\icons\WidgetIconInvitations::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 1,
            ],
            [
                'classname' => \open20\amos\invitations\widgets\icons\WidgetIconInvitationsAll::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 1,
            ]
        ];
    }
}