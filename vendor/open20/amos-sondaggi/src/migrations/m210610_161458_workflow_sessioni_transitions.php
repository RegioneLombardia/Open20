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
use open20\amos\sondaggi\models\SondaggiRisposteSessioni;

class m210610_161458_workflow_sessioni_transitions extends AmosMigrationWorkflow
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
     * In this method there are the new workflow status transitions configurations.
     * @return array
     */
    private function workflowTransitionsConf()
    {
        return [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => SondaggiRisposteSessioni::WORKFLOW,
                'start_status_id' => SondaggiRisposteSessioni::WORKFLOW_STATUS_BOZZA_KEY,
                'end_status_id' => SondaggiRisposteSessioni::WORKFLOW_STATUS_INVIATO_KEY
            ]
        ];
    }
}