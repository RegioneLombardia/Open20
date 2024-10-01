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

/**
 * Class m211112_105813_fix_ticket_export_permission
 */
class m211112_105813_fix_ticket_export_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'TICKET_EXPORT',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AMMINISTRATORE_TICKET'],
                    'removeParents' => ['ADMIN']
                ]
            ]
        ];
    }
}
