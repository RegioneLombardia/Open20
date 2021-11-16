<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m170317_082718_add_role_community_member_and_permissions
 */
class m170317_082718_add_role_community_member_and_permissions extends AmosMigrationPermissions
{
    /**
     * Use this function to map permissions, roles and associations between permissions and roles. If you don't need to
     * to add or remove any permissions or roles you have to delete this method.
     */
    protected function setAuthorizations()
    {
        $prefixStr = 'Permesso per la dashboard per il widget ';
        $this->authorizations = [
            [
                'name' => 'COMMUNITY_MEMBER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Role to view community plugin and apply/be invited to a community',
                'ruleName' => null,     // This is a string
            ],
            [
                'name' => 'COMMUNITY_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di lettura per il model Community',
                'ruleName' => null,
                'parent' => ['COMMUNITY_MEMBER'],
                'dontRemove' => true
            ],
            [
                'name' => \open20\amos\community\widgets\icons\WidgetIconCommunityDashboard::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconCommunityDashboard',
                'ruleName' => null,
                'parent' => ['COMMUNITY_MEMBER'],
                'dontRemove' => true
            ],
            [
                'name' => \open20\amos\community\widgets\icons\WidgetIconCommunity::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconCommunity',
                'ruleName' => null,
                'parent' => ['COMMUNITY_MEMBER'],
                'dontRemove' => true
            ],
            [
                'name' => \open20\amos\community\widgets\icons\WidgetIconMyCommunities::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconMyCommunities',
                'ruleName' => null,
                'parent' => ['COMMUNITY_MEMBER'],
                'dontRemove' => true
            ],
        ];
    }
}
