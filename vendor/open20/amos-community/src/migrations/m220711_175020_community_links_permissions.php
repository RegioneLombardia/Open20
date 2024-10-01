<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;
use open20\amos\community\rules\UpdateCommunityLinksRule;

/**
 * Class m220711_175020_community_links_permissions*/
class m220711_175020_community_links_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'COMMUNITYLINKS_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model CommunityLinks',
                'ruleName' => null,
                'parent' => [
                    'COMMUNITY_LINKS_ADMIN',
                    'COMMUNITY_LINKS_READER'
                ]
            ],
            [
                'name' => 'COMMUNITYLINKS_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model CommunityLinks',
                'ruleName' => null,
                'parent' => [
                    'COMMUNITY_LINKS_ADMIN',
                    'COMMUNITY_LINKS_READER'
                ]
            ],
            [
                'name' => 'COMMUNITYLINKS_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model CommunityLinks',
                'ruleName' => null,
                'parent' => [
                    'COMMUNITY_LINKS_ADMIN',
                    UpdateCommunityLinksRule::className()
                ]
            ],
            [
                'name' => 'COMMUNITYLINKS_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model CommunityLinks',
                'ruleName' => null,
                'parent' => [
                    'COMMUNITY_LINKS_ADMIN',
                    UpdateCommunityLinksRule::className()
                ]
            ],

        ];
    }
}
