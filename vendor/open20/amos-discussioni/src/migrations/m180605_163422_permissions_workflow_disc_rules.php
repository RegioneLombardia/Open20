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
class m180605_163422_permissions_workflow_disc_rules extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => \open20\amos\discussioni\rules\workflow\DiscussioniToValidateWorkflowRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Check permissio to validate',
                'ruleName' => \open20\amos\discussioni\rules\workflow\DiscussioniToValidateWorkflowRule::className(),
                'parent' => ['CREATORE_DISCUSSIONI', 'FACILITATORE_DISCUSSIONI', 'DiscussionValidate', 'VALIDATORE_DISCUSSIONI']
            ],
            [
                'name' => 'DiscussioniTopicWorkflow/DAVALIDARE',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        \open20\amos\discussioni\rules\workflow\DiscussioniToValidateWorkflowRule::className()
                    ],
                    'removeParents' => [
                        'CREATORE_DISCUSSIONI', 'FACILITATORE_DISCUSSIONI', 'DiscussionValidate', 'VALIDATORE_DISCUSSIONI'
                    ]
                ],
            ],

        ];
    }
}
