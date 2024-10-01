<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m230605_125705_socialwall_content_creator_permission_to_basic_user */
class m230605_125705_socialwall_content_creator_permission_to_basic_user extends AmosMigrationPermissions
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
                'description' => 'Permesso di SOCIALWALL_CONTENT_CREATOR a BASIC_USER',
                'ruleName' => null,
                'parent' => ['BASIC_USER']
            ],
        ];
    }
}
