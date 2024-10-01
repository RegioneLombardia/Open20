<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m190223_121253_add_export_tickets_permission
 */
class m190223_121253_add_export_tickets_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'EXPORT_TICKETS',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per esportare i ticket',
                'parent' => ['AMMINISTRATORE_TICKET']
            ]
        ];
    }
}
