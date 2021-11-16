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
 * Class m171220_153854_add_create_subcommunities_permission
 */
class m171220_153854_add_create_subcommunities_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'CreateSubcommunities',
                'type' => Permission::TYPE_PERMISSION,
                'ruleName' => \open20\amos\community\rules\CreateSubcommunitiesRule::className(),
                'description' => 'Permission to create subcommunities under a specific community parent',
            ],
        ];
    }
}
