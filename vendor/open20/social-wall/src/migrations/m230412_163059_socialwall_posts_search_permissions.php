<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m230412_163059_socialwall_posts_search_permissions*/
class m230412_163059_socialwall_posts_search_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' =>  'SOCIALWALLPOSTSSTATICSEARCH_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model SocialwallPostsStaticSearch',
                'ruleName' => null,
                'parent' => ['SOCIALWALL_CONTENT_CREATOR']
            ],
            [
                'name' =>  'SOCIALWALLPOSTSSTATICSEARCH_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model SocialwallPostsStaticSearch',
                'ruleName' => null,
                'parent' => ['SOCIALWALL_CONTENT_CREATOR']
            ],
            [
                'name' =>  'SOCIALWALLPOSTSSTATICSEARCH_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model SocialwallPostsStaticSearch',
                'ruleName' => null,
                'parent' => ['SOCIALWALL_CONTENT_CREATOR']
            ],
            [
                'name' =>  'SOCIALWALLPOSTSSTATICSEARCH_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model SocialwallPostsStaticSearch',
                'ruleName' => null,
                'parent' => ['SOCIALWALL_CONTENT_CREATOR']
            ],

        ];
    }
}
