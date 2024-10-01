<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\collaborations\migrations
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\collaborations\models\CollaborationProposals;
use yii\rbac\Permission;

/**
 * 
 */
class m221219_164824_update_collaboration_proposals_workflow_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return \yii\helpers\ArrayHelper::merge(
            $this->setPluginRoles(),
            $this->setWorkflowStatusPermissions()
        );
    }

    /**
     * Plugin roles
     *
     * @return array
     */
    private function setPluginRoles()
    {
        return [];
    }

    /**
     *
     * @return array
     */
    private function setWorkflowStatusPermissions()
    {
        return [
            [
                'name' => CollaborationProposals::STATUS_CLOSED,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'CollaborationProposals workflow status STATUS_CLOSED permission',
                'parent' => [
                    'BASIC_USER',
                ],
            ],
        ];
    }

}