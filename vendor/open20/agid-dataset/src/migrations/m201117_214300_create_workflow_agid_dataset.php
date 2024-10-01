<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\results\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWorkflow;


/**
 * Class m201117_214300_create_workflow_agid_dataset
 */
class m201117_214300_create_workflow_agid_dataset extends AmosMigrationWorkflow
{
    const AGID_DATASET_WORKFLOW = 'AgidDatasetWorkflow';

    
    /**
     * @inheritdoc
     */
    protected function setWorkflow()
    {
        return \yii\helpers\ArrayHelper::merge(
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
                'id' => self::AGID_DATASET_WORKFLOW,
                'initial_status_id' => 'DRAFT'
            ],
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
                'workflow_id' => self::AGID_DATASET_WORKFLOW,
                'label' => 'Bozza',
                'sort_order' => '0'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'VALIDATED',
                'workflow_id' => self::AGID_DATASET_WORKFLOW,
                'label' => 'Validato',
                'sort_order' => '1'
            ]
        ];
    }

    
    /**
     * In this method there are the new workflow status transitions configurations.
     * 
     * DRAFT -> VALIDATED
     * VALIDATED -> DRAFT
     * 
     * @return array
     */
    private function workflowTransitionsConf()
    {
        return [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::AGID_DATASET_WORKFLOW,
                'start_status_id' => 'DRAFT',
                'end_status_id' => 'VALIDATED'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::AGID_DATASET_WORKFLOW,
                'start_status_id' => 'VALIDATED',
                'end_status_id' => 'DRAFT'
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

            // "DRAFT" status
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::AGID_DATASET_WORKFLOW,
                'status_id' => 'DRAFT',
                'key' => 'description',
                'value' => 'Salva in Bozza'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::AGID_DATASET_WORKFLOW,
                'status_id' => 'DRAFT',
                'key' => 'buttonLabel',
                'value' => 'Salva in Bozza'
            ],

            // "VALIDATED" status
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::AGID_DATASET_WORKFLOW,
                'status_id' => 'VALIDATED',
                'key' => 'description',
                'value' => 'Valida'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::AGID_DATASET_WORKFLOW,
                'status_id' => 'VALIDATED',
                'key' => 'buttonLabel',
                'value' => 'Valida'
            ],
        ];
    }

}