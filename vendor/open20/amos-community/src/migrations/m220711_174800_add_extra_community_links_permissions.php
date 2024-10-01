<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;
use open20\amos\community\rules\UpdateCommunityLinksRule;


/**
 * Class m220728_155500_add_extra_community_links_permissions*/
class m220711_174800_add_extra_community_links_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => UpdateCommunityLinksRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model CommunityLinks',
                'ruleName' => UpdateCommunityLinksRule::className(),
                'parent' => ['COMMUNITY_LINKS_READER'],
                'children' => ['COMMUNITYLINKS_UPDATE', 'COMMUNITYLINKS_DELETE']
            ],
        ];
    }
}
