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
 * Class m180118_153854_edit_community_create_permission
 */
class m180118_153854_edit_community_create_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'COMMUNITY_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'update' => true,
                'newValues' => [
                    'addParents' => ['CreateSubcommunities']
                ]
            ],
        ];
    }
}
