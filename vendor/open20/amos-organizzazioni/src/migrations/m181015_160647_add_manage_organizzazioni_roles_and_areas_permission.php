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
 * Class m181015_160647_add_manage_organizzazioni_roles_and_areas_permission
 */
class m181015_160647_add_manage_organizzazioni_roles_and_areas_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'MANAGE_ORGANIZZAZIONI_ROLES_AND_AREAS',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di gestione per ruoli e aree per ogni organizzazione associata a un utente',
                'parent' => ['AMMINISTRATORE_ORGANIZZAZIONI']
            ]
        ];
    }
}
