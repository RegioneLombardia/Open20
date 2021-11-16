<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m180605_163522_permissions_workflow_rules
 */
class m200214_181059_permission_publish_to_site extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'DISCUSSIONI_PUBLISHER_FRONTEND',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to publish Discussion in frontend',
            ],
        ];
    }
}
