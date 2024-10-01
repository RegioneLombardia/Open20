<?php
use open20\amos\core\migration\AmosMigrationWorkflow;
use open20\amos\collaborations\models\CollaborationProposals;

/**
 * Class m230622_152418_mod_status_to_workflow
 */
class m230622_152418_mod_status_to_workflow extends AmosMigrationWorkflow
{
    /**
     * @inheritdoc
     */
    protected function setWorkflow()
    {
        return [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'REFUSED',
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'remove' => true
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'REFUSED',
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'label' => 'Rifiutata',
                'sort_order' => '4'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'status_id' => 'TOVALIDATE',
                'key' => 'description',
                'remove' => true
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'status_id' => 'TOVALIDATE',
                'key' => 'description',
                'value' => 'Richiedi validazione proposta'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'status_id' => 'REFUSED',
                'key' => 'buttonLabel',
                'remove' => true
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'status_id' => 'REFUSED',
                'key' => 'buttonLabel',
                'value' => 'Rifiuta'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'status_id' => 'REFUSED',
                'key' => 'description',
                'remove' => true
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'status_id' => 'REFUSED',
                'key' => 'description',
                'value' => 'Rifiuta proposta'
            ],
        ];
    }
}