<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m230329_165605_socialwall_tokens_permissions*/
class m230329_165605_socialwall_tokens_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' =>  'SOCIALWALLTOKENS_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model SocialwallTokens',
                'ruleName' => null,
                'parent' => ['SOCIALWALL_ADMIN']
            ],
            [
                'name' =>  'SOCIALWALLTOKENS_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model SocialwallTokens',
                'ruleName' => null,
                'parent' => ['SOCIALWALL_ADMIN', 'SOCIALWALL_CONTENT_CREATOR']
            ],
            [
                'name' =>  'SOCIALWALLTOKENS_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model SocialwallTokens',
                'ruleName' => null,
                'parent' => ['SOCIALWALL_ADMIN']
            ],
            [
                'name' =>  'SOCIALWALLTOKENS_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model SocialwallTokens',
                'ruleName' => null,
                'parent' => ['SOCIALWALL_ADMIN']
            ],

        ];
    }
}
