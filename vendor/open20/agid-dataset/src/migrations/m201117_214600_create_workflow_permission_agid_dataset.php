<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\results\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m201117_214600_create_workflow_permission_agid_dataset
 */
class m201117_214600_create_workflow_permission_agid_dataset extends AmosMigrationPermissions
{
    const AGID_DATASET_WORKFLOW = 'AgidDatasetWorkflow';

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
     * set Workflow permission for the all state of workflow for AGID ORGANIZATIONAL UNIT
     *
     * @return array
     */
    private function setWorkflowPermissions()
    {
        return [
            [
                'name' => self::AGID_DATASET_WORKFLOW . '/DRAFT',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Workflow status permission: Draft',
                'ruleName' => null,
                'parent' => ['ADMIN', 'BASIC_USER']
            ],
            [
                'name' => self::AGID_DATASET_WORKFLOW . '/VALIDATED',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Workflow status permission: validated',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ]
        ];
    }
    
}
