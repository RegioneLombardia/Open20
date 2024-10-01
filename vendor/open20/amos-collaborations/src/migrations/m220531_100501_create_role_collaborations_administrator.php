<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


class m220531_100501_create_role_collaborations_administrator extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'COLLABORATIONS_ADMINISTRATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Amministratore del plugin Amos Collaborations',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'COLLABORATIONS_READER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo con permesso di lettura del plugin Amos Collaborations',
                'ruleName' => null,
                'parent' => ['COLLABORATIONS_ADMINISTRATOR']
            ],
            [
                'name' => 'COLLABORATIONS_STAFF',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Staff del plugin Amos Collaborations',
                'ruleName' => null,
                //'parent' => ['ADMIN']
            ],
        ];
    }
}
