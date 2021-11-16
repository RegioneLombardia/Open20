<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m170322_143053_add_community_read_permission
 */
class m170322_143053_add_community_read_permission extends AmosMigrationPermissions
{
    /**
     * Use this function to map permissions, roles and associations between permissions and roles. If you don't need to
     * to add or remove any permissions or roles you have to delete this method.
     */
    protected function setAuthorizations()
    {
        /**
         * Modificata migration per fare in modo che non elimini nulla.
         * Era doppia l'associazione dei ruoli e mancava il dontRemove
         */
        $this->authorizations = [
            [
                'name' => 'COMMUNITY_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to read community models',
                'ruleName' => null,
                'dontRemove' => true
            ],
        ];
    }
}
