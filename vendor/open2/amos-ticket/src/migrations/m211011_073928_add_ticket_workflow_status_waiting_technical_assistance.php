<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open2\amos\ticket\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWorkflow;
use yii\helpers\ArrayHelper;

/**
 * Class m211011_073928_add_ticket_workflow_status_waiting_technical_assistance
 */
class m211011_073928_add_ticket_workflow_status_waiting_technical_assistance extends AmosMigrationWorkflow
{
    const WORKFLOW_NAME = 'TicketWorkflow';
    const WORKFLOW_WAITING_TECHNICAL_ASSISTANCE = 'WAITINGTECHNICALASSISTANCE';
    
    /**
     * @inheritdoc
     */
    protected function setWorkflow()
    {
        return ArrayHelper::merge(
            parent::setWorkflow(),
            $this->workflowStatusConf(),
            $this->workflowTransitionsConf(),
            $this->workflowMetadataConf()
        );
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
                'id' => self::WORKFLOW_WAITING_TECHNICAL_ASSISTANCE,
                'workflow_id' => self::WORKFLOW_NAME,
                'label' => 'In attesa di assistenza tecnica',
                'sort_order' => '3'
            ]
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
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'WAITING',
                'end_status_id' => self::WORKFLOW_WAITING_TECHNICAL_ASSISTANCE
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'PROCESSING',
                'end_status_id' => self::WORKFLOW_WAITING_TECHNICAL_ASSISTANCE
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => self::WORKFLOW_WAITING_TECHNICAL_ASSISTANCE,
                'end_status_id' => 'CLOSED'
            ]
        ];
    }
    
    /**
     * In this method there are the new workflow metadata configurations.
     * @return array
     */
    private function workflowMetadataConf()
    {
        return [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_WAITING_TECHNICAL_ASSISTANCE,
                'key' => 'label',
                'value' => '#' . self::WORKFLOW_WAITING_TECHNICAL_ASSISTANCE . '_label'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_WAITING_TECHNICAL_ASSISTANCE,
                'key' => 'buttonLabel',
                'value' => '#' . self::WORKFLOW_WAITING_TECHNICAL_ASSISTANCE . '_buttonLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_WAITING_TECHNICAL_ASSISTANCE,
                'key' => 'description',
                'value' => '#' . self::WORKFLOW_WAITING_TECHNICAL_ASSISTANCE . '_description'
            ]
        ];
    }
}
