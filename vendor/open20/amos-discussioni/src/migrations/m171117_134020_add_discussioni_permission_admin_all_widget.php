<?php

use yii\db\Migration;
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

class m171117_134020_add_discussioni_permission_admin_all_widget extends AmosMigrationPermissions
{
    protected function setAuthorizations()
    {
        $this->authorizations = [
            [
                'name' => \open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopicAdminAll::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission description',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DISCUSSIONI' ],
                'dontRemove' => true
            ],

        ];
    }
}
