<?php
use open20\amos\core\migration\AmosMigrationWorkflow;
use open20\amos\collaborations\models\CollaborationProposals;
use yii\helpers\ArrayHelper;

/**
 * Class m230626_121135_add_status_to_workflow
 */
class m230626_121135_add_status_to_workflow extends AmosMigrationWorkflow
{
    /**
     * @inheritdoc
     */
    protected function setWorkflow()
    {
        return ArrayHelper::merge(
            parent::setWorkflow(),
            $this->workflowTransitionsConf()
        );
    }

    /**
     * Restituisce le configurazioni inerenti alle transizioni
     * @return array Array di configurazione
     */
    private function workflowTransitionsConf() {
        return [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'start_status_id' => 'REFUSED',
                'end_status_id' => 'DRAFT'
            ],
        ];
    }
}