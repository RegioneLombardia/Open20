<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m180327_162827_add_auth_item_een_archived*/
class m220220_183031_add_permission_luyaconfigs extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';

        return [
            [
                'name' => 'LUYACONFIG_ADMINISTRATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo amministatore configurazioni di luya',
                'ruleName' => null,
            ],

        ];
    }
}
