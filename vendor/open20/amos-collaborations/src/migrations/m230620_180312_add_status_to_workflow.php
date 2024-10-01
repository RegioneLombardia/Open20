<?php
use open20\amos\core\migration\AmosMigrationWorkflow;
use open20\amos\collaborations\models\CollaborationProposals;

/**
 * Class m230620_180312_add_status_to_workflow
 */
class m230620_180312_add_status_to_workflow extends AmosMigrationWorkflow
{
    /**
     * @inheritdoc
     */
    protected function setWorkflow()
    {
        return [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'TOVALIDATE',
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'label' => 'Da Validare',
                'sort_order' => '3'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'REFUSED',
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'label' => 'Rifiutato',
                'sort_order' => '4'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'start_status_id' => 'DRAFT',
                'end_status_id' => 'TOVALIDATE'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'start_status_id' => 'TOVALIDATE',
                'end_status_id' => 'REFUSED'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'start_status_id' => 'TOVALIDATE',
                'end_status_id' => 'PUBLISHED'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'status_id' => 'TOVALIDATE',
                'key' => 'buttonLabel',
                'value' => 'Richiedi Validazione'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'status_id' => 'TOVALIDATE',
                'key' => 'description',
                'value' => 'Richiedi Validazione'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'status_id' => 'REFUSED',
                'key' => 'buttonLabel',
                'value' => 'Rifiutato'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'status_id' => 'REFUSED',
                'key' => 'description',
                'value' => 'Rifiutato'
            ],
        ];
    }
}