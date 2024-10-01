<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\organizzazioni\models\Profilo;
use open20\amos\organizzazioni\rules\workflow\ProfiloToValidateWorkflowRule;
use yii\rbac\Permission;

/**
 * Class m210121_230112_validator_permissions
 */
class m210121_230112_validator_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'PROFILO_VALIDATOR',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AMMINISTRATORE_ORGANIZZAZIONI', 'VALIDATOR'],
                    'removeParents' => ['ADMIN']
                ]
            ],
            [
                'name' => 'ProfiloValidate',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to validate a Profilo with cwh query',
                'ruleName' => \open20\amos\core\rules\ValidatorUpdateContentRule::className(),
                'parent' => ['PROFILO_VALIDATOR', 'VALIDATED_BASIC_USER'],
                'children' => ['PROFILO_UPDATE']
            ],
            [
                'name' => ProfiloToValidateWorkflowRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Check if you are an author',
                'ruleName' => ProfiloToValidateWorkflowRule::className(),
                'parent' => ['PROFILO_CREATOR', 'ProfiloValidate', 'PROFILO_VALIDATOR']
            ],
            [
                'name' => 'PROFILO_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'update' => true,
                'newValues' => [
                    'removeParents' => ['PROFILO_VALIDATOR']
                ]
            ],
            [
                'name' => Profilo::PROFILO_WORKFLOW_STATUS_DRAFT,
                'update' => true,
                'newValues' => [
                    'addParents' => ['ProfiloValidate'],
                    'removeParents' => ['AMMINISTRATORE_ORGANIZZAZIONI', 'PROFILO_VALIDATOR']
                ]
            ],
            [
                'name' => Profilo::PROFILO_WORKFLOW_STATUS_TOVALIDATE,
                'update' => true,
                'newValues' => [
                    'addParents' => [ProfiloToValidateWorkflowRule::className()],
                    'removeParents' => ['AMMINISTRATORE_ORGANIZZAZIONI', 'PROFILO_CREATOR', 'PROFILO_VALIDATOR']
                ]
            ],
            [
                'name' => Profilo::PROFILO_WORKFLOW_STATUS_VALIDATED,
                'update' => true,
                'newValues' => [
                    'addParents' => ['ProfiloValidate'],
                    'removeParents' => ['AMMINISTRATORE_ORGANIZZAZIONI']
                ]
            ]
        ];
    }
}
