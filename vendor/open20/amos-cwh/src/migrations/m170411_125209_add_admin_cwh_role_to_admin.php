<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m170411_125209_add_admin_cwh_role_to_admin
 */
class m170411_125209_add_admin_cwh_role_to_admin extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setAuthorizations()
    {
        $this->authorizations = [
            [
                'name' => 'AMMINISTRATORE_CWH',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo per amministrare CWH',
                'ruleName' => null,
                'parent' => ['ADMIN'],
                'dontRemove' => true
            ]
        ];

    }
}
