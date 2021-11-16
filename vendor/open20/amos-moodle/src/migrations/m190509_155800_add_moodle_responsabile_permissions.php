<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m190509_155800_add_moodle_responsabile_permissions
 */
class m190509_155800_add_moodle_responsabile_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'MOODLE_RESPONSABILE',
                'type' => Permission::TYPE_ROLE,
                'description' => 'A responsible in Moodle Plugin',
            ],
        ];
    }
}
