<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m200624_183920_add_auth_item_widget_icon_moodle_dashboard*/
class m200624_183920_add_auth_item_widget_icon_moodle_dashboard extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';

        return [
                [
                    'name' => \open20\amos\moodle\widgets\icons\WidgetIconMoodleDashboard::className(),
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => $prefixStr . 'WidgetIconWidgetIconMoodleDashboard',
                    'ruleName' => null,
                    'parent' => ['ADMIN','MOODLE_ADMIN','MOODLE_STUDENT']
                ]

            ];
    }
}
