<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\chat\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;

/**
 * Class m170711_103614_add_chat_basic_user_perms
 */
class m170711_103614_add_chat_basic_user_perms extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'AMMINISTRATORE_CHAT',
                'update' => true,
                'newValues' => [
                    'addParents' => ['BASIC_USER']
                ]
            ]
        ];
    }
}
