<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m171031_160001_add_auth_item_importatore_comuni*/
class m230706_181500_add_widget_video_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';

        return [
            [
                'name' => \open20\video\widgets\icons\WidgetIconVideo::class,
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconVideo',
                'ruleName' => null,
                'parent' => [
                    'ADMIN',
                    'BASIC_USER'
                ]
            ],
            [
                'name' =>  \open20\video\widgets\graphics\WidgetGraphicVideo::class,
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetGraphicVideo',
                'ruleName' => null,
                'parent' => [
                    'ADMIN',
                    'BASIC_USER'
                ]
            ]
        ];
    }
}