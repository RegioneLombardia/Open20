<?php

/**
 * Lombardia Informatica S.p.A.
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
class m180223_130124_associate_rule_to_facilitator_role extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => ValidateUserProfileWorkflowRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission for FACILITATOR role to validate a user',
                'ruleName' => ValidateUserProfileWorkflowRule::className(),
                'parent' => ['FACILITATOR']
            ],
            [
                'name' => 'UserProfileWorkflow/VALIDATED',
                'update' => true,
                'newValues' => [
                    'addParents' => [ValidateUserProfileWorkflowRule::className()],
                    'removeParents' => ['FACILITATOR']
                ]
            ]
        ];
    }
}
