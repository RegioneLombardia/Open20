<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m170914_094129_add_validator_role
 */
class m180105_183329_add_impersonate_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'IMPERSONATE_USERS',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Impersonate other users',
                'parent' => ['ADMIN']
            ]
        ];
    }
}
