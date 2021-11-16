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
 * Class m170719_122922_permissions_community
 */
class m170719_122922_permissions_community extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => \open20\amos\community\rbac\UpdateOwnCommunityProfile::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di aggiornare il proprio profilo della community',
                'ruleName' => \open20\amos\community\rbac\UpdateOwnCommunityProfile::className(),
                'parent' => ['ADMIN', 'BASIC_USER']
            ],
            [
                'name' => \open20\amos\community\rbac\UpdateOwnNetworkCommunity::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di aggiornare le community nella tua rete',
                'ruleName' => \open20\amos\community\rbac\UpdateOwnNetworkCommunity::className(),
                'parent' => ['ADMIN', 'BASIC_USER']
            ]
        ];
    }
}
