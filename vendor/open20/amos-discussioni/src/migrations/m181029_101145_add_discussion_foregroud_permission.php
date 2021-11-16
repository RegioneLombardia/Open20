<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m170914_094129_add_validator_role
 */
class m181029_101145_add_discussion_foregroud_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'DISCUSSION_FOREGROUD_PERMISSION',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'FOREGROUD FLAG PERMISSION',
                'parent' => ['ADMIN']
            ]
        ];
    }
}

