<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;

/**
 * Class m170529_104009_add_basic_user_perms
 */
class m170529_104009_add_basic_user_perms extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'LETTORE_DOCUMENTI',
                'update' => true,
                'newValues' => [
                    'addParents' => ['BASIC_USER']
                ]
            ],
            [
                'name' => 'CREATORE_DOCUMENTI',
                'update' => true,
                'newValues' => [
                    'addParents' => ['VALIDATED_BASIC_USER']
                ]
            ]
        ];
    }
}
