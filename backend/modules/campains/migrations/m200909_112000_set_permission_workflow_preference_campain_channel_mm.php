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
 * Class m200909_112000_set_permission_workflow_preference_campain_channel_mm
 */
class m200909_112000_set_permission_workflow_preference_campain_channel_mm extends AmosMigrationPermissions
{
    const PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW = "PreferenceCampainChannelMmWorkflow";
    
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
                'name' => self::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW . '/TOBEAPPROVED',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Workflow status permission: TO BE APPROVED',
                'ruleName' => null,
                'parent' => ['PC_BASIC_USER']
            ],
            [
                'name' => self::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW . '/APPROVED',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Workflow status permission: APPROVED',
                'ruleName' => null,
                'parent' => ['PC_APPROVER']
            ],
            [
                'name' => self::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW . '/SENDINGINPROGRESS',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Workflow status permission: SENDING IN PROGRESS',
                'ruleName' => null,
                'parent' => ['PC_CRON']
            ],
            [
                'name' => self::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW . '/SENT',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Workflow status permission: SENT',
                'ruleName' => null,
                'parent' => ['PC_CRON']
            ]
        ];
    }

}