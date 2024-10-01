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
 * Class m210607_170208_add_import_organizations_permission
 */
class m210607_170208_add_import_organizations_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'IMPORT_ORGANIZATIONS',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission for import organizations',
                'parent' => ['AMMINISTRATORE_ORGANIZZAZIONI']
            ]
        ];
    }
}
