<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

class m191204_102512_add_tokens_widgets_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';
        return [
            [
                'name' => \open20\amos\socialauth\widgets\icons\WidgetIconManageTokens::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconManageTokens',
                'parent' => ['ADMIN']
            ]
        ];
    }
}
