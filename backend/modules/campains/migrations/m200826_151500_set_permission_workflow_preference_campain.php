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
 * Class m200826_151500_set_permission_workflow_preference_campain
 */
class m200826_151500_set_permission_workflow_preference_campain extends AmosMigrationPermissions
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
                'name' => self::PREFERENCE_CAMPAIN_WORKFLOW . '/INMODIFICATION',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Workflow status permission: IN MODIFICATION',
                'ruleName' => null,
                'parent' => ['PC_BASIC_USER']
            ],
            [
                'name' => self::PREFERENCE_CAMPAIN_WORKFLOW . '/TOSEND',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Workflow status permission: TO SEND',
                'ruleName' => null,
                'parent' => ['PC_BASIC_USER']
            ],
            [
                'name' => self::PREFERENCE_CAMPAIN_WORKFLOW . '/SENDINGINPROGRESS',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Workflow status permission: SENDING IN PROGRESS',
                'ruleName' => null,
                'parent' => ['PC_BASIC_USER']
            ],
            [
                'name' => self::PREFERENCE_CAMPAIN_WORKFLOW . '/SENT',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Workflow status permission: SENT',
                'ruleName' => null,
                'parent' => ['PC_BASIC_USER']
            ]
        ];
    }


}