<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m200624_173356_add_auth_item_moodle_user_courses*/
class m200624_173356_add_auth_item_moodle_user_courses extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';

        return [
                [
                    'name' =>  \open20\amos\moodle\widgets\icons\WidgetIconMoodleUserCourses::className(),
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => $prefixStr . 'WidgetIconMoodleUserCourses',
                    'ruleName' => null,
                    'parent' => ['ADMIN','MOODLE_ADMIN','MOODLE_STUDENT']
                ]

            ];
    }
}
