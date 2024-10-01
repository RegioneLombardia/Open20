<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m230419_140552_socialwall_posts_profiles_permissions*/
class m230419_140552_socialwall_posts_profiles_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' =>  'SOCIALWALLPOSTSPROFILES_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model SocialwallPostsProfiles',
                'ruleName' => null,
                'parent' => ['SOCIALWALL_CONTENT_CREATOR']
            ],
            [
                'name' =>  'SOCIALWALLPOSTSPROFILES_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model SocialwallPostsProfiles',
                'ruleName' => null,
                'parent' => ['SOCIALWALL_CONTENT_CREATOR', 'BASIC_USER']
            ],
            [
                'name' =>  'SOCIALWALLPOSTSPROFILES_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model SocialwallPostsProfiles',
                'ruleName' => null,
                'parent' => ['SOCIALWALL_CONTENT_CREATOR']
            ],
            [
                'name' =>  'SOCIALWALLPOSTSPROFILES_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model SocialwallPostsProfiles',
                'ruleName' => null,
                'parent' => ['SOCIALWALL_CONTENT_CREATOR']
            ],

        ];
    }
}
