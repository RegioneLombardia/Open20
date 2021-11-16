<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m181018_134854_add_admin_tag_tabs_permission
 */
class m191125_102154_role_facilitator_external extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'FACILITATOR_EXTERNAL',
                'type' => Permission::TYPE_ROLE,
                'description' => "Role facilitator external",
                'parent' => [],
                'children' => [
                    'VALIDATOR',
                    'PARTNER_PROF_EXPR_OF_INT_ADMIN_FACILITATOR',
                    'USERPROFILE_READ'
                ]
            ],
        ];
    }
}
