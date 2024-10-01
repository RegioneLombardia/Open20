<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;

/**
 * Class m210623_130908_fix_associate_contacts_permission
 */
class m210623_130908_fix_associate_contacts_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'ASSOCIATE_CONTACTS',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AMMINISTRATORE_UTENTI']
                ]
            ]
        ];
    }
}
