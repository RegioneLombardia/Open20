<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m230411_234657_socialwall_posts_permissions*/
class m230411_234657_socialwall_posts_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' =>  'SOCIALWALLPOSTS_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model SocialwallPosts',
                'ruleName' => null,
                'parent' => ['SOCIALWALL_CONTENT_CREATOR']
            ],
            [
                'name' =>  'SOCIALWALLPOSTS_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model SocialwallPosts',
                'ruleName' => null,
                'parent' => ['SOCIALWALL_CONTENT_CREATOR', 'BASIC_USER']
            ],
            [
                'name' =>  'SOCIALWALLPOSTS_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model SocialwallPosts',
                'ruleName' => null,
                'parent' => ['SOCIALWALL_CONTENT_CREATOR']
            ],
            [
                'name' =>  'SOCIALWALLPOSTS_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model SocialwallPosts',
                'ruleName' => null,
                'parent' => ['SOCIALWALL_CONTENT_CREATOR']
            ],

        ];
    }
}
