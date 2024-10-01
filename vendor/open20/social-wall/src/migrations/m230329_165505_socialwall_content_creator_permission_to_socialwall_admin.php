<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m230329_165505_socialwall_content_creator_permission_to_socialwall_admin */
class m230329_165505_socialwall_content_creator_permission_to_socialwall_admin extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'SOCIALWALL_CONTENT_CREATOR',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di SOCIALWALL_CONTENT_CREATOR a SOCIALWALL_ADMIN',
                'ruleName' => null,
                'parent' => ['SOCIALWALL_ADMIN']
            ],
        ];
    }
}
