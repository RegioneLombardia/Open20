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
use yii\rbac\Permission;

/**
 * Class m170719_122922_permissions_community
 */
class m180605_175022_permissions_workflow_rules extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => \open20\amos\documenti\rules\workflow\DocumentiToValidateWorkflowRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Check',
                'ruleName' => \open20\amos\documenti\rules\workflow\DocumentiToValidateWorkflowRule::className(),
                'parent' => ['CREATORE_DOCUMENTI', 'FACILITATORE_DOCUMENTI', 'DocumentValidate', 'VALIDATORE_DOCUMENTI']
            ],
            [
                'name' => 'DocumentiWorkflow/DAVALIDARE',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        \open20\amos\documenti\rules\workflow\DocumentiToValidateWorkflowRule::className()
                    ],
                    'removeParents' => [
                        'CREATORE_DOCUMENTI', 'FACILITATORE_DOCUMENTI', 'DocumentValidate', 'VALIDATORE_DOCUMENTI'
                    ]
                ],
            ],

        ];
    }
}
