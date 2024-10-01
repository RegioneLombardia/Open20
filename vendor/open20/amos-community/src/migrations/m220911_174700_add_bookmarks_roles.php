<?php

use yii\rbac\Permission;

class m220911_174700_add_bookmarks_roles extends \open20\amos\core\migration\AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';
        return [
            [
                'name' => 'BOOKMARKS_ADMIN',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo che permette di amministrare i community link',
                'ruleName' => null,
                'parent' => [
                    'ADMIN',
                    'AMMINISTRATORE_COMMUNITY'
                ]
            ],
            [
                'name' => 'BOOKMARKS_READER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo che permette di vedere i community link',
                'ruleName' => null,
                'parent' => [
                    'BASIC_USER',
                    'COMMUNITY_READER'
                ]
            ]
        ];
    }
}