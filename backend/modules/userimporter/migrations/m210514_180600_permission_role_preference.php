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


class m210514_180600_permission_role_preference extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [

            [
                'name' => 'AMMINISTRATORE_USERIMPORTTASK',
                'update' => true,
                'newValues' => [
                    'addParents' => ['PC_BASIC_USER'],
                ]
            ],
            
        ];

    }
}
