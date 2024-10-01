<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;
use open20\amos\collaborations\models\CollaborationProposals;
use open20\amos\core\migration\AmosMigrationWorkflow;

class m220610_115000_add_collaboration_proposals_workflow_permissions extends AmosMigrationPermissions
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
        return [
        ];
    }

    /**
     * Workflow statuses permissions
     *
     * @return array
     */
    private function setWorkflowStatusPermissions()
    {
        return [
            [
                'name' => CollaborationProposals::STATUS_DRAFT,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'CollaborationProposals workflow status DRAFT permission',
                'parent' => ['COLLABORATIONS_ADMINISTRATOR'],
            ],
            [
                'name' => CollaborationProposals::STATUS_PUBLISHED,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'CollaborationProposals workflow status PUBLISHED permission',
                'parent' => ['COLLABORATIONS_ADMINISTRATOR'],
            ],
            [
                'name' => CollaborationProposals::STATUS_CLOSED,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'CollaborationProposals workflow status CLOSED permission',
                'parent' => ['COLLABORATIONS_ADMINISTRATOR'],
            ],
        ];
    }
}