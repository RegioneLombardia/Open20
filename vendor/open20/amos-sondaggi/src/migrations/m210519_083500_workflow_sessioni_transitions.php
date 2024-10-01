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

class m210519_083500_workflow_sessioni_transitions extends AmosMigrationWorkflow
{

    /**
     * @inheritdoc
     */
    protected function setWorkflow()
    {
        return ArrayHelper::merge(
                parent::setWorkflow(), $this->workflowConf(), $this->workflowStatusConf(),
                $this->workflowTransitionsConf(), $this->workflowMetadataConf()
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
                'id' => SondaggiRisposteSessioni::WORKFLOW,
                'initial_status_id' => SondaggiRisposteSessioni::WORKFLOW_STATUS_BOZZA_KEY
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
                'id' => SondaggiRisposteSessioni::WORKFLOW_STATUS_BOZZA_KEY,
                'workflow_id' => SondaggiRisposteSessioni::WORKFLOW,
                'label' => 'Bozza',
                'sort_order' => '0'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => SondaggiRisposteSessioni::WORKFLOW_STATUS_RICHIESTA_INVIO_KEY,
                'workflow_id' => SondaggiRisposteSessioni::WORKFLOW,
                'label' => 'Richiesta di invio',
                'sort_order' => '1'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => SondaggiRisposteSessioni::WORKFLOW_STATUS_INVIATO_KEY,
                'workflow_id' => SondaggiRisposteSessioni::WORKFLOW,
                'label' => 'Inviato',
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
                'workflow_id' => SondaggiRisposteSessioni::WORKFLOW,
                'start_status_id' => SondaggiRisposteSessioni::WORKFLOW_STATUS_BOZZA_KEY,
                'end_status_id' => SondaggiRisposteSessioni::WORKFLOW_STATUS_RICHIESTA_INVIO_KEY
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => SondaggiRisposteSessioni::WORKFLOW,
                'start_status_id' => SondaggiRisposteSessioni::WORKFLOW_STATUS_RICHIESTA_INVIO_KEY,
                'end_status_id' => SondaggiRisposteSessioni::WORKFLOW_STATUS_INVIATO_KEY
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => SondaggiRisposteSessioni::WORKFLOW,
                'start_status_id' => SondaggiRisposteSessioni::WORKFLOW_STATUS_INVIATO_KEY,
                'end_status_id' => SondaggiRisposteSessioni::WORKFLOW_STATUS_BOZZA_KEY
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => SondaggiRisposteSessioni::WORKFLOW,
                'start_status_id' => SondaggiRisposteSessioni::WORKFLOW_STATUS_RICHIESTA_INVIO_KEY,
                'end_status_id' => SondaggiRisposteSessioni::WORKFLOW_STATUS_BOZZA_KEY
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
            // Bozza
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => SondaggiRisposteSessioni::WORKFLOW,
                'status_id' => SondaggiRisposteSessioni::WORKFLOW_STATUS_BOZZA_KEY,
                'key' => 'label',
                'value' => '#'.SondaggiRisposteSessioni::WORKFLOW_STATUS_BOZZA_KEY.'_label'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => SondaggiRisposteSessioni::WORKFLOW,
                'status_id' => SondaggiRisposteSessioni::WORKFLOW_STATUS_BOZZA_KEY,
                'key' => 'buttonLabel',
                'value' => '#'.SondaggiRisposteSessioni::WORKFLOW_STATUS_BOZZA_KEY.'_buttonLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => SondaggiRisposteSessioni::WORKFLOW,
                'status_id' => SondaggiRisposteSessioni::WORKFLOW_STATUS_BOZZA_KEY,
                'key' => 'description',
                'value' => '#'.SondaggiRisposteSessioni::WORKFLOW_STATUS_BOZZA_KEY.'_description'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => SondaggiRisposteSessioni::WORKFLOW,
                'status_id' => SondaggiRisposteSessioni::WORKFLOW_STATUS_BOZZA_KEY,
                'key' => 'order',
                'value' => '1'
            ],
            // Richiesta di invio
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => SondaggiRisposteSessioni::WORKFLOW,
                'status_id' => SondaggiRisposteSessioni::WORKFLOW_STATUS_RICHIESTA_INVIO_KEY,
                'key' => 'label',
                'value' => '#'.SondaggiRisposteSessioni::WORKFLOW_STATUS_RICHIESTA_INVIO_KEY.'_label'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => SondaggiRisposteSessioni::WORKFLOW,
                'status_id' => SondaggiRisposteSessioni::WORKFLOW_STATUS_RICHIESTA_INVIO_KEY,
                'key' => 'buttonLabel',
                'value' => '#'.SondaggiRisposteSessioni::WORKFLOW_STATUS_RICHIESTA_INVIO_KEY.'_buttonLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => SondaggiRisposteSessioni::WORKFLOW,
                'status_id' => SondaggiRisposteSessioni::WORKFLOW_STATUS_RICHIESTA_INVIO_KEY,
                'key' => 'description',
                'value' => '#'.SondaggiRisposteSessioni::WORKFLOW_STATUS_RICHIESTA_INVIO_KEY.'_description'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => SondaggiRisposteSessioni::WORKFLOW,
                'status_id' => SondaggiRisposteSessioni::WORKFLOW_STATUS_RICHIESTA_INVIO_KEY,
                'key' => 'order',
                'value' => '2'
            ],
            // Inviata
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => SondaggiRisposteSessioni::WORKFLOW,
                'status_id' => SondaggiRisposteSessioni::WORKFLOW_STATUS_INVIATO_KEY,
                'key' => 'label',
                'value' => '#'.SondaggiRisposteSessioni::WORKFLOW_STATUS_INVIATO_KEY.'_label'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => SondaggiRisposteSessioni::WORKFLOW,
                'status_id' => SondaggiRisposteSessioni::WORKFLOW_STATUS_INVIATO_KEY,
                'key' => 'buttonLabel',
                'value' => '#'.SondaggiRisposteSessioni::WORKFLOW_STATUS_INVIATO_KEY.'_buttonLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => SondaggiRisposteSessioni::WORKFLOW,
                'status_id' => SondaggiRisposteSessioni::WORKFLOW_STATUS_INVIATO_KEY,
                'key' => 'description',
                'value' => '#'.SondaggiRisposteSessioni::WORKFLOW_STATUS_INVIATO_KEY.'_description'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => SondaggiRisposteSessioni::WORKFLOW,
                'status_id' => SondaggiRisposteSessioni::WORKFLOW_STATUS_INVIATO_KEY,
                'key' => 'order',
                'value' => '3'
            ],
        ];
    }
}