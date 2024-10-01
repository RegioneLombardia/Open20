<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m210603_104401_add_organizzazioni_delete_from_organization_permission
 */
class m210603_104401_add_organizzazioni_delete_from_organization_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'REMOVE_ORGANIZZAZIONI_FROM_USER',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Delete permission to remove organization of an user',
                'parent' => ['ASSOCIATE_ORGANIZZAZIONI_TO_USER']
            ],
            [
                'name' => 'REMOVE_PROFILO_FROM_USER_PERMISSION',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Delete permission to remove organization of an user',
                'parent' => ['ASSOCIATE_PROFILO_TO_USER_PERMISSION']
            ]
        ];
    }
}
