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
 * Class m201028_102641_add_manage_ticket_referees_notifications_permission
 */
class m201028_102641_add_manage_ticket_referees_notifications_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'MANAGE_REFEREE_CATEGORIES_TICKET_NOTIFICATIONS',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per i referenti di categoria di scegliere se ricevere o meno le notifiche',
                'parent' => ['REFERENTE_TICKET', 'AMMINISTRATORE_TICKET']
            ]
        ];
    }
}
