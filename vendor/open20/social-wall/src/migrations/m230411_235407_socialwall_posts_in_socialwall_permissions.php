<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m230411_235407_socialwall_posts_in_socialwall_permissions*/
class m230411_235407_socialwall_posts_in_socialwall_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'SOCIALWALLPOSTSINSOCIALWALL_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model SocialwallPostsInSocialwall',
                'ruleName' => null,
                'parent' => ['SOCIALWALL_CONTENT_CREATOR']
            ],
            [
                'name' => 'SOCIALWALLPOSTSINSOCIALWALL_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model SocialwallPostsInSocialwall',
                'ruleName' => null,
                'parent' => ['SOCIALWALL_CONTENT_CREATOR', 'BASIC_USER']
            ],
            [
                'name' => 'SOCIALWALLPOSTSINSOCIALWALL_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model SocialwallPostsInSocialwall',
                'ruleName' => null,
                'parent' => ['SOCIALWALL_CONTENT_CREATOR']
            ],
            [
                'name' => 'SOCIALWALLPOSTSINSOCIALWALL_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model SocialwallPostsInSocialwall',
                'ruleName' => null,
                'parent' => ['SOCIALWALL_CONTENT_CREATOR']
            ],

        ];
    }
}
