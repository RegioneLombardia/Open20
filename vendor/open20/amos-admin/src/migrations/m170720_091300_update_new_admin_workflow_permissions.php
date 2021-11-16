<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;

/**
 * Class m170720_091300_update_new_admin_workflow_permissions
 */
class m170720_091300_update_new_admin_workflow_permissions extends AmosMigrationPermissions
{
    const WORKFLOW_NAME = 'UserProfileWorkflow';
    
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => self::WORKFLOW_NAME . '/DRAFT',
                'update' => true,
                'newValues' => [
                    'addParents' => ['FACILITATOR']
                ]
            ]
        ];
    }
}
