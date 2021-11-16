<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    
 * @category   
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m201106_144300_create_workflow_permissions_agid_person
 */
class m201106_144300_create_workflow_permissions_agid_service extends AmosMigrationPermissions
{
    const AGID_SERVICE_WORKFLOW = 'AgidServiceWorkflow';

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
     * set Workflow permission for the all state of workflow for AGID SERVICE    
     *
     * @return array
     */
    private function setWorkflowPermissions()
    {
        return [
            [
                'name' => self::AGID_SERVICE_WORKFLOW . '/DRAFT',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Workflow status permission: Draft',
                'ruleName' => null,
                'parent' => ['ADMIN', 'ADMIN_FE', 'BASIC_USER']
            ],
            [
                'name' => self::AGID_SERVICE_WORKFLOW . '/VALIDATED',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Workflow status permission: validated',
                'ruleName' => null,
                'parent' => ['ADMIN','ADMIN_FE']
            ]
        ];
    }
    
}
