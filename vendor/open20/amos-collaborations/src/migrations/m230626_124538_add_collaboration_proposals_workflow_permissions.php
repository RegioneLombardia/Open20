<?php
use open20\amos\collaborations\models\CollaborationProposals;
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m230626_124538_add_collaboration_proposals_workflow_permissions
 */
class m230626_124538_add_collaboration_proposals_workflow_permissions extends AmosMigrationPermissions
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
                'update' => true,
        	'newValues' => [
                    'addParents' => ['VALIDATED_BASIC_USER']
        	]
            ],
        ];
    }
}