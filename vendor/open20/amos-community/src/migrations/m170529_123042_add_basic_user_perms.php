<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

class m170529_123042_add_basic_user_perms extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'COMMUNITY_READER',
                'update' => true,
                'newValues' => [
                    'addParents' => ['BASIC_USER']
                ]
            ],
            [
                'name' => 'COMMUNITY_CREATOR',
                'update' => true,
                'newValues' => [
                    'addParents' => ['VALIDATED_BASIC_USER']
                ]
            ]
        ];
    }
}

