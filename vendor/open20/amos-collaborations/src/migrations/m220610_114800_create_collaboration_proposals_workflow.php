<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @category   CategoryName
 */

use yii\helpers\ArrayHelper;
use open20\amos\core\migration\AmosMigrationWorkflow;
use open20\amos\collaborations\models\CollaborationProposals;

class m220610_114800_create_collaboration_proposals_workflow extends AmosMigrationWorkflow
{

    /**
     * @inheritdoc
     */
    protected function setWorkflow()
    {
        return ArrayHelper::merge(
            parent::setWorkflow(),
            $this->workflowConf(),
            $this->workflowStatusConf(),
            $this->workflowTransitionsConf(),
            $this->workflowMetadataConf()
        );
    }

    /**
     * In this method there are the new workflow configuration.
     * @return array
     */
    private function workflowConf()
    {
        return [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW,
                'id' => CollaborationProposals::WORKFLOW,
                'initial_status_id' => 'DRAFT'
            ]
        ];
    }

    /**
     * In this method there are the new workflow statuses configurations.
     * @return array
     */
    private function workflowStatusConf()
    {
        return [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'DRAFT',
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'label' => 'Bozza',
                'sort_order' => '0'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'PUBLISHED',
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'label' => 'Pubblicata',
                'sort_order' => '1'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'CLOSED',
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'label' => 'Chiusa',
                'sort_order' => '2'
            ],
        ];
    }

    /**
     * In this method there are the new workflow status transitions configurations.
     * @return array
     */
    private function workflowTransitionsConf()
    {
        return [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'start_status_id' => 'DRAFT',
                'end_status_id' => 'PUBLISHED'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'start_status_id' => 'PUBLISHED',
                'end_status_id' => 'DRAFT'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'start_status_id' => 'PUBLISHED',
                'end_status_id' => 'CLOSED'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'start_status_id' => 'CLOSED',
                'end_status_id' => 'PUBLISHED'
            ],
        ];
    }

    /**
     * In this method there are the new workflow metadata configurations.
     * @return array
     */
    private function workflowMetadataConf()
    {
        return [
            // DRAFT
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'status_id' => 'DRAFT',
                'key' => 'buttonLabel',
                'value' => 'Rimetti in bozza'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'status_id' => 'DRAFT',
                'key' => 'description',
                'value' => 'Rimetti in bozza'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'status_id' => 'DRAFT',
                'key' => 'PUBLISHED_label',
                'value' => 'Togli dalla pubblicazione'
            ],
            // PUBLISHED
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'status_id' => 'PUBLISHED',
                'key' => 'buttonLabel',
                'value' => 'Pubblica'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'status_id' => 'PUBLISHED',
                'key' => 'description',
                'value' => 'Pubblica proposta'
            ],
            // CLOSED
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'status_id' => 'CLOSED',
                'key' => 'buttonLabel',
                'value' => 'Chiudi'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'status_id' => 'CLOSED',
                'key' => 'description',
                'value' => 'La proposta non sarà più modificabile'
            ],
        ];
    }
}
