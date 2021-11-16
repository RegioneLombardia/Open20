<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m180327_162827_add_auth_item_een_archived*/
class m181022_144427_permission_widget_download_chat_assistance extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';

        return [
            [
                'name' => \open20\amos\chat\widgets\graphics\WidgetGraphicChatAssistance::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'Permission widget graphinc assistance chat',
                'ruleName' => null,
                'parent' => [ 'BASIC_USER', 'AMMINISTRATORE_CHAT']
            ],

        ];
    }
}
