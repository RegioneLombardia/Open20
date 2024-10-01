<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m190731_182105_community_user_field_default_val_permissions*/
class m190731_182105_community_user_field_default_val_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'COMMUNITYUSERFIELDDEFAULTVAL_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model CommunityUserFieldDefaultVal',
                    'ruleName' => null,
                    'parent' => ['AMMINISTRATORE_COMMUNITY', \open20\amos\community\rules\CommunityManagerRoleRule::className()]
                ],
                [
                    'name' =>  'COMMUNITYUSERFIELDDEFAULTVAL_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model CommunityUserFieldDefaultVal',
                    'ruleName' => null,
                    'parent' => ['COMMUNITY_MEMBER', 'AMMINISTRATORE_COMMUNITY', \open20\amos\community\rules\CommunityManagerRoleRule::className()]
                    ],
                [
                    'name' =>  'COMMUNITYUSERFIELDDEFAULTVAL_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model CommunityUserFieldDefaultVal',
                    'ruleName' => null,
                    'parent' => ['AMMINISTRATORE_COMMUNITY', \open20\amos\community\rules\CommunityManagerRoleRule::className()]
                ],
                [
                    'name' =>  'COMMUNITYUSERFIELDDEFAULTVAL_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model CommunityUserFieldDefaultVal',
                    'ruleName' => null,
                    'parent' => ['AMMINISTRATORE_COMMUNITY', \open20\amos\community\rules\CommunityManagerRoleRule::className()]
                ],

            ];
    }
}
