<?php
use open20\amos\collaborations\models\CollaborationProposals;
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m230620_181853_add_collaboration_proposals_workflow_permissions
 */
class m230620_181853_add_collaboration_proposals_workflow_permissions extends AmosMigrationPermissions
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
                'name' => CollaborationProposals::STATUS_TOVALIDATE,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'CollaborationProposals workflow status TOVALIDATE permission',
                'parent' => ['COLLABORATIONS_ADMINISTRATOR'],
            ],
            [
                'name' => CollaborationProposals::STATUS_REFUSED,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'CollaborationProposals workflow status REFUSED permission',
                'parent' => ['COLLABORATIONS_ADMINISTRATOR'],
            ],
        ];
    }
}