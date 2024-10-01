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
 * Class m181022_105021_ticket_permissions
 */
class m181022_105021_ticket_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'TICKET_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model Ticket',
                'ruleName' => null,
                'parent' => ['OPERATORE_TICKET']
            ],
            [
                'name' => 'TICKET_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model Ticket',
                'ruleName' => null,
                'parent' => ['OPERATORE_TICKET']
            ],
            [
                'name' => 'TICKET_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model Ticket',
                'ruleName' => null,
                'parent' => ['OPERATORE_TICKET']
            ],
            [
                'name' => 'TICKET_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model Ticket',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_TICKET']
            ],
        ];
    }
}
