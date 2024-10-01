<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    care-for-workers\platform\common\console\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;

/**
 * Class m190502_132139_add_cfw_invitations_permissions_to_responsabile_struttura_role
 */
class m190704_104300_add_invitations_perms_to_manager_organizzazione extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'INVITATIONS_BASIC_USER',
                'update' => true,
                'newValues' => [
                    'addParents' => ['MANAGER_ORGANIZZAZIONE']
                ]
            ],
            [
                'name' => 'INVITATIONS_ADMINISTRATOR',
                'update' => true,
                'newValues' => [
                    'addParents' => ['MANAGER_ORGANIZZAZIONE']
                ]
            ],
            [
                'name' => 'ADD_EMPLOYEE_TO_ORGANIZATION_PERMISSION',
                'update' => true,
                'newValues' => [
                    'addParents' => ['MANAGER_ORGANIZZAZIONE']
                ]
            ]
        ];
    }
}
