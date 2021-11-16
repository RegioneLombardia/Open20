<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\results\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m200909_111500_set_permission_workflow_preference_campain
 */
class m200909_111500_set_permission_workflow_preference_campain extends AmosMigrationPermissions
{
    const PREFERENCE_CAMPAIN_WORKFLOW = "PreferenceCampainWorkflow";
    
    /**
     * Use this function to map permissions, roles and associations between permissions and roles. If you don't need to
     * to add or remove any permissions or roles you have to delete this method.
     */
    protected function setAuthorizations()
    {
        $this->authorizations = array_merge(
            $this->setWorkflowPermissions()
        );
    }


    /**
     * set Workflow permission for the all state of workflow
     *
     * @return void
     */
    private function setWorkflowPermissions()
    {
        return [
            [
                'name' => 'PC_CRON',
                'type' => Permission::TYPE_ROLE,
                'description' => 'CRON',
                'ruleName' => null,
                'parent' => [],
            ],
            [
                'name' => 'PC_APPROVER',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['PC_EDITOR'],
                ]
            ],
            [
                'name' => 'PC_EDITOR',
                'update' => true,
                'newValues' => [
                    'addParents' => ['PC_APPROVER'],
                ]
            ],
            [
                'name' => self::PREFERENCE_CAMPAIN_WORKFLOW . '/TOBEAPPROVED',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Workflow status permission: TOBEAPPROVED',
                'ruleName' => null,
                'parent' => ['PC_BASIC_USER']
            ],
            [
                'name' => self::PREFERENCE_CAMPAIN_WORKFLOW . '/APPROVED',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Workflow status permission: APPROVED',
                'ruleName' => null,
                'parent' => ['PC_APPROVER']
            ],
            [
                'name' => self::PREFERENCE_CAMPAIN_WORKFLOW . '/SENDINGINPROGRESS',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Workflow status permission: SENDING IN PROGRESS',
                'ruleName' => null,
                'parent' => ['PC_CRON']
            ],
            [
                'name' => self::PREFERENCE_CAMPAIN_WORKFLOW . '/SENT',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Workflow status permission: SENT',
                'ruleName' => null,
                'parent' => ['PC_CRON']
            ]
        ];
    }


}