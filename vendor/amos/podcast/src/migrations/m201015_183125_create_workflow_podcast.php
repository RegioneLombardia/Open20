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

class m201015_183125_create_workflow_podcast extends AmosMigrationWorkflow
{
    
    const PODCAST_WORKFLOW = 'PodcastWorkflow';
//    const PODCAST_WORKFLOW = 'PodcastWorkflow';


    
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
                'id' => self::PODCAST_WORKFLOW,
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
                'workflow_id' => self::PODCAST_WORKFLOW,
                'label' => 'Modifica in corso',
                'sort_order' => '1'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'REQUESTPUBLICATION',
                'workflow_id' => self::PODCAST_WORKFLOW,
                'label' => 'In attesa di pubblicazione',
                'sort_order' => '2'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'REJECTED',
                'workflow_id' => self::PODCAST_WORKFLOW,
                'label' => 'Rifiutata',
                'sort_order' => '3'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'PUBLISHED',
                'workflow_id' => self::PODCAST_WORKFLOW,
                'label' => 'Pubblicato',
                'sort_order' => '4'
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
                'workflow_id' => self::PODCAST_WORKFLOW,
                'start_status_id' => 'DRAFT',
                'end_status_id' => 'REQUESTPUBLICATION'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::PODCAST_WORKFLOW,
                'start_status_id' => 'DRAFT',
                'end_status_id' => 'PUBLISHED'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::PODCAST_WORKFLOW,
                'start_status_id' => 'REQUESTPUBLICATION',
                'end_status_id' => 'PUBLISHED'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::PODCAST_WORKFLOW,
                'start_status_id' => 'REQUESTPUBLICATION',
                'end_status_id' => 'REJECTED'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::PODCAST_WORKFLOW,
                'start_status_id' => 'REJECTED',
                'end_status_id' => 'REQUESTPUBLICATION'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::PODCAST_WORKFLOW,
                'start_status_id' => 'PUBLISHED',
                'end_status_id' => 'REJECTED'
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

            // "Draft" status
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' =>  self::PODCAST_WORKFLOW,
                'status_id' => 'REQUESTPUBLICATION',
                'key' => 'buttonLabel',
                'value' => 'Richiedi pubblicazione'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' =>  self::PODCAST_WORKFLOW,
                'status_id' => 'REQUESTPUBLICATION',
                'key' => 'description',
                'value' => 'Richiedi pubblicazione'
            ],

            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' =>  self::PODCAST_WORKFLOW,
                'status_id' => 'PUBLISHED',
                'key' => 'buttonLabel',
                'value' => 'Pubblica'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' =>  self::PODCAST_WORKFLOW,
                'status_id' => 'PUBLISHED',
                'key' => 'description',
                'value' => 'Pubblica'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' =>  self::PODCAST_WORKFLOW,
                'status_id' => 'REJECTED',
                'key' => 'buttonLabel',
                'value' => 'Rifiuta'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' =>  self::PODCAST_WORKFLOW,
                'status_id' => 'REJECTED',
                'key' => 'description',
                'value' => 'Rifiuta'
            ],
//
//
        ];
    }


}
