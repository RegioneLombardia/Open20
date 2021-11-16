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
 * Class m201216_151500_update_permissions
 */
class m201216_151500_update_permissions extends AmosMigrationPermissions
{
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
                'name' => 'PREFERENCECAMPAINCONTAINER_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['PC_BASIC_USER'],
                ],
            ]
        ];
    }
}
