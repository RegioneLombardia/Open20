<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m190607_110218_community_user_field_permissions*/
class m190607_110218_community_user_field_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'COMMUNITYUSERFIELD_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model CommunityUserField',
                    'ruleName' => null,
                    'parent' => ['AMMINISTRATORE_COMMUNITY', \open20\amos\community\rules\CommunityManagerRoleRule::className()]
                ],
                [
                    'name' =>  'COMMUNITYUSERFIELD_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model CommunityUserField',
                    'ruleName' => null,
                    'parent' => ['AMMINISTRATORE_COMMUNITY' ,'COMMUNITY_MEMBER']
                    ],
                [
                    'name' =>  'COMMUNITYUSERFIELD_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model CommunityUserField',
                    'ruleName' => null,
                    'parent' => ['AMMINISTRATORE_COMMUNITY', \open20\amos\community\rules\CommunityManagerRoleRule::className()]
                ],
                [
                    'name' =>  'COMMUNITYUSERFIELD_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model CommunityUserField',
                    'ruleName' => null,
                    'parent' => ['AMMINISTRATORE_COMMUNITY',\open20\amos\community\rules\CommunityManagerRoleRule::className()]
                ],
            // ------------------------

            [
                'name' =>  'COMMUNITYUSERFIELDVAL_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model CommunityUserFieldVal',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_COMMUNITY','COMMUNITY_MEMBER']
            ],
            [
                'name' =>  'COMMUNITYUSERFIELDVAL_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model CommunityUserFieldVal',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_COMMUNITY','COMMUNITY_MEMBER']
            ],
            [
                'name' =>  'COMMUNITYUSERFIELDVAL_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model CommunityUserFieldVal',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_COMMUNITY','COMMUNITY_MEMBER']
            ],
            [
                'name' =>  'COMMUNITYUSERFIELDVAL_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model CommunityUserFieldVal',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_COMMUNITY','COMMUNITY_MEMBER']
            ],

            // ------------

            [
                'name' =>  'COMMUNITYUSERFIELDTYPE_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model CommunityUserFieldType',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_COMMUNITY',\open20\amos\community\rules\CommunityManagerRoleRule::className()]
            ],
            [
                'name' =>  'COMMUNITYUSERFIELDTYPE_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model CommunityUserFieldType',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_COMMUNITY',\open20\amos\community\rules\CommunityManagerRoleRule::className()]
            ],
            [
                'name' =>  'COMMUNITYUSERFIELDTYPE_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model CommunityUserFieldType',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_COMMUNITY', \open20\amos\community\rules\CommunityManagerRoleRule::className()]
            ],
            [
                'name' =>  'COMMUNITYUSERFIELDTYPE_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model CommunityUserFieldType',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_COMMUNITY', \open20\amos\community\rules\CommunityManagerRoleRule::className()]
            ],


        ];
    }
}
