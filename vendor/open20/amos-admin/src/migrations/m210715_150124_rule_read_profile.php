<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\admin\rules\ValidateUserProfileWorkflowRule;
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m170724_074724_associate_default_facilitator_rule_to_facilitator_role
 */
class m210715_150124_rule_read_profile extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => \open20\amos\admin\rules\UserProfileReadRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission read User Profile',
                'ruleName' => \open20\amos\admin\rules\UserProfileReadRule::className(),
                'parent' => ['BASIC_USER'],
                'children' => ['USERPROFILE_READ']
            ],
            [
                'name' => 'USERPROFILE_READ',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['BASIC_USER']
                ]
            ]
        ];
    }
}
