<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\sondaggi\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;

/**
 * Class m180409_162125_add_amministratore_sondaggi_to_admin
 */
class m180409_162125_add_amministratore_sondaggi_to_admin extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'AMMINISTRAZIONE_SONDAGGI',
                'update' => true,
                'newValues' => [
                    'addParents' => ['ADMIN']
                ]
            ]
        ];
    }
}
