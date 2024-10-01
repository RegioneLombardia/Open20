<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m171031_160001_add_auth_item_importatore_comuni*/
class m180115_102600_add_permission_videoconference extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for create videoconference';

        return [
            [
                'name' =>  'VIDEOCONF_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr,
                'ruleName' => null,
                'parent' => ['BASIC_USER']
            ]

        ];
    }
}