<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;

/**
 * Class m170719_122922_permissions_community
 */
class m180702_175022_permissions_workflow_rules_fix extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'DocumentiWorkflow/DAVALIDARE',
                'update' => true,
                'newValues' => [
                    'removeParents' => [
                        'open20\amos\news\rules\workflow\NewsToValidateWorkflowRule'
                    ],
                    'addParents' => [
                        \open20\amos\documenti\rules\workflow\DocumentiToValidateWorkflowRule::className()
                    ],
                ],
            ],

        ];
    }
}
