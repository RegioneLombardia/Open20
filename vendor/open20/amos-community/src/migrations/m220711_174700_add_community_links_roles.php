<?php

use yii\rbac\Permission;

class m220711_174700_add_community_links_roles extends \open20\amos\core\migration\AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';
        return [
            [
                'name' => 'COMMUNITY_LINKS_ADMIN',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo che permette di amministrare i community link',
                'ruleName' => null,
                'parent' => [
                    'ADMIN',
                    'AMMINISTRATORE_COMMUNITY'
                ]
            ],
            [
                'name' => 'COMMUNITY_LINKS_READER',
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