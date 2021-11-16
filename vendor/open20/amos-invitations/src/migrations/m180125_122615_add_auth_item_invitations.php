<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m180125_122615_add_auth_item_invitations*/
class m180125_122615_add_auth_item_invitations extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';

        return [
            [
                'name' =>  \open20\amos\invitations\widgets\icons\WidgetIconInvitations::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconInvitations',
                'ruleName' => null,
                'parent' => ['INVITATIONS_BASIC_USER']
            ],
            [
                'name' =>  \open20\amos\invitations\widgets\icons\WidgetIconInvitationsAll::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconInvitationsAll',
                'ruleName' => null,
                'parent' => ['INVITATIONS_ADMINISTRATOR']
            ]

        ];
    }
}