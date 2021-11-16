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
class m180605_180022_permissions_workflow_rules extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => \open20\amos\events\rules\workflow\EventsToValidateWorkflowRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Check',
                'ruleName' =>  \open20\amos\events\rules\workflow\EventsToValidateWorkflowRule::className(),
                'parent' => ['EVENTS_ADMINISTRATOR', 'EVENTS_CREATOR', 'PLATFORM_EVENTS_VALIDATOR', 'EventValidate', 'EVENTS_VALIDATOR']
            ],
            [
                'name' => 'EventWorkflow/PUBLISHREQUEST',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        \open20\amos\events\rules\workflow\EventsToValidateWorkflowRule::className(),
                    ],
                    'removeParents' => [
                        'EVENTS_ADMINISTRATOR', 'EVENTS_CREATOR', 'PLATFORM_EVENTS_VALIDATOR', 'EventValidate', 'EVENTS_VALIDATOR'
                    ]
                ],
            ],

        ];
    }
}
