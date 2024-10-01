<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;


class m200410_105823_permission_role_to_admin extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [

            [
                'name' => 'AMMINISTRATORE_CHRONOTASK',
                'update' => true,
                'newValues' => [
                    'addParents' => ['ADMIN'],
                ]
            ],
            
        ];

    }
}
