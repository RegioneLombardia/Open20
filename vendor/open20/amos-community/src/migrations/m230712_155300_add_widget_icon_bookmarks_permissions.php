<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m230712_155300_add_widget_icon_bookmarks_permissions */
class m230712_155300_add_widget_icon_bookmarks_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';

        return [
            [
                'name' =>  \open20\amos\community\widgets\icons\WidgetIconBookmarks::class,
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconBookmarks',
                'ruleName' => null,
                'parent' => [
                    'ADMIN',
                    'BASIC_USER'
                ]
            ]
        ];
    }
}