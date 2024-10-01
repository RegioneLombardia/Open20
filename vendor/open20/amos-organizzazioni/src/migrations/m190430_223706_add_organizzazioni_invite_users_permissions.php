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
 * Class m190430_223706_add_organizzazioni_invite_users_permissions
 */
class m190430_223706_add_organizzazioni_invite_users_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'ADD_EMPLOYEE_TO_ORGANIZATION_PERMISSION',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per associare un utente a una organizzazione da una organizzazione',
                'parent' => ['PROFILO_UPDATE']
            ],
            [
                'name' => 'MANAGE_EMPLOYEE_TO_ORGANIZATION_PERMISSION',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per associare un utente a una organizzazione da una organizzazione',
                'parent' => ['PROFILO_UPDATE']
            ]
        ];
    }
}
