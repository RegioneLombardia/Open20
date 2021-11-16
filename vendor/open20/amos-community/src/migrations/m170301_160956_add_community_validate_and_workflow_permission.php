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
use open20\amos\community\models\Community;

/**
 * Class m170301_160956_add_community_validate_and_workflow_permission
 */
class m170301_160956_add_community_validate_and_workflow_permission extends AmosMigrationPermissions
{
    /**
     * Use this function to map permissions, roles and associations between permissions and roles. If you don't need to
     * to add or remove any permissions or roles you have to delete this method.
     */
    protected function setAuthorizations()
    {
        $this->authorizations = [
            [
                'name' => 'COMMUNITY_VALIDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to validate a community with no specific domain',
                'ruleName' => null,     // This is a string
                'parent' => ['AMMINISTRATORE_COMMUNITY']
            ],
            [
                'name' => Community::COMMUNITY_WORKFLOW_STATUS_DRAFT,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permession workflow community staus draft',
                'ruleName' => null,
                'parent' => ['COMMUNITY_CREATE', 'COMMUNITY_UPDATE', 'COMMUNITY_VALIDATE', 'AMMINISTRATORE_COMMUNITY']
            ],
            [
                'name' => Community::COMMUNITY_WORKFLOW_STATUS_TO_VALIDATE,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permession workflow community staus to validate',
                'ruleName' => null,
                'parent' => ['COMMUNITY_CREATE', 'COMMUNITY_UPDATE', 'COMMUNITY_VALIDATE', 'AMMINISTRATORE_COMMUNITY']
            ],
            [
                'name' => Community::COMMUNITY_WORKFLOW_STATUS_VALIDATED,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permession workflow community staus validated',
                'ruleName' => null,
                'parent' => ['COMMUNITY_VALIDATE', 'AMMINISTRATORE_COMMUNITY']
            ],
            [
                'name' => Community::COMMUNITY_WORKFLOW_STATUS_NOT_VALIDATED,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permession workflow community staus not validated',
                'ruleName' => null,
                'parent' => ['COMMUNITY_VALIDATE', 'AMMINISTRATORE_COMMUNITY']
            ]
        ];
    }

}
