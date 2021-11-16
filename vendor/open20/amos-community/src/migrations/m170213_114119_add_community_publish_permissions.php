<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m170213_114119_add_community_publish_permissions
 */
class m170213_114119_add_community_publish_permissions extends AmosMigrationPermissions
{
    /**
     * Use this function to map permissions, roles and associations between permissions and roles. If you don't need to
     * to add or remove any permissions or roles you have to delete this method.
     */
    protected function setAuthorizations()
    {
        $this->authorizations = [
            [
                'name' => 'COMMUNITY_PUBLISH',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to publish a community',
                'ruleName' => null,     // This is a string
                'parent' => ['AMMINISTRATORE_COMMUNITY']
            ],
        ];
    }
}
