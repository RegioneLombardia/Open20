<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m230712_163600_update_widget_formazione_permissions */
class m230712_163600_update_widget_video_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';

        return [
            [
                'name' =>  \open20\video\widgets\icons\WidgetIconVideo::class,
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconVideo',
                'ruleName' => null,
                    'update' => true,
                    'newValues' => [
                        'addParents' => [
                            'VALIDATED_BASIC_USER'
                        ]
                    ]
            ],
            [
                'name' =>  \open20\video\widgets\graphics\WidgetGraphicVideo::class,
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetGraphicVideo',
                'ruleName' => null,
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'VALIDATED_BASIC_USER'
                    ]
                ]
            ]
        ];
    }
}