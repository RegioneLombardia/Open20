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
use yii\rbac\Permission;
use open20\amos\organizzazioni\models\Profilo;

/**
 * Class m210121_223112_profilo_validate_and_workflow_permission
 */
class m210121_223112_profilo_validate_and_workflow_permission extends AmosMigrationPermissions
{
    /**
     * Use this function to map permissions, roles and associations between permissions and roles. If you don't need to
     * to add or remove any permissions or roles you have to delete this method.
     */
    protected function setAuthorizations()
    {
        $this->authorizations = [
            [
                'name' => 'PROFILO_VALIDATOR',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to validate a community with no specific domain',
                'ruleName' => null,     // This is a string
                'parent' => ['AMMINISTRATORE_ORGANIZZAZIONI']
            ],
            [
                'name' => Profilo::PROFILO_WORKFLOW_STATUS_DRAFT,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permession workflow community staus draft',
                'ruleName' => null,
                'parent' => ['PROFILO_CREATE', 'PROFILO_UPDATE', 'PROFILO_VALIDATOR', 'AMMINISTRATORE_ORGANIZZAZIONI']
            ],
            [
                'name' => Profilo::PROFILO_WORKFLOW_STATUS_TOVALIDATE,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permession workflow community staus to validate',
                'ruleName' => null,
                'parent' => ['PROFILO_CREATE', 'PROFILO_UPDATE', 'PROFILO_VALIDATOR', 'AMMINISTRATORE_ORGANIZZAZIONI']
            ],
            [
                'name' => Profilo::PROFILO_WORKFLOW_STATUS_VALIDATED,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permession workflow community staus validated',
                'ruleName' => null,
                'parent' => ['PROFILO_VALIDATOR', 'AMMINISTRATORE_ORGANIZZAZIONI']
            ]
        ];
    }

}
