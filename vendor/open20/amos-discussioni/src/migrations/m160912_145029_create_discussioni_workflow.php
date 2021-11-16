<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWorkflow;
use yii\helpers\ArrayHelper;

/**
 * Class m160912_145029_create_discussioni_workflow
 */
class m160912_145029_create_discussioni_workflow extends AmosMigrationWorkflow
{
    const WORKFLOW_NAME = 'DiscussioniTopicWorkflow';

    /**
     * @inheritdoc
     */
    protected function setWorkflow()
    {
        return ArrayHelper::merge(
            $this->workflowConf(),
            $this->workflowStatusConf(),
            $this->workflowTransitionsConf()
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
                'id' => self::WORKFLOW_NAME,
                'initial_status_id' => 'BOZZA'
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
                'id' => 'BOZZA',
                'workflow_id' => self::WORKFLOW_NAME,
                'label' => 'Bozza',
                'sort_order' => '1'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'ATTIVA',
                'workflow_id' => self::WORKFLOW_NAME,
                'label' => 'Attiva',
                'sort_order' => '2'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'DISATTIVA',
                'workflow_id' => self::WORKFLOW_NAME,
                'label' => 'Disattiva',
                'sort_order' => '3'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'DAVALIDARE',
                'workflow_id' => self::WORKFLOW_NAME,
                'label' => 'Da validare',
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
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'BOZZA',
                'end_status_id' => 'DAVALIDARE'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'DAVALIDARE',
                'end_status_id' => 'ATTIVA'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'DAVALIDARE',
                'end_status_id' => 'DISATTIVA'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'DISATTIVA',
                'end_status_id' => 'BOZZA'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'ATTIVA',
                'end_status_id' => 'BOZZA'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'ATTIVA',
                'end_status_id' => 'DAVALIDARE'
            ]
        ];
    }
}
