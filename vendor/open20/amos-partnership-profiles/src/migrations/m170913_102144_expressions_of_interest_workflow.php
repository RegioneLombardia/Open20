<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\partnershipprofiles\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWorkflow;
use yii\helpers\ArrayHelper;

/**
 * Class m170913_102144_expressions_of_interest_workflow
 */
class m170913_102144_expressions_of_interest_workflow extends AmosMigrationWorkflow
{
    const WORKFLOW_NAME = 'ExpressionsOfInterestWorkflow';
    
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
                'id' => self::WORKFLOW_NAME,
                'initial_status_id' => 'DRAFT'
            ]
        ];
    }
    
    /**
     * In this method there are the new workflow statuses configurations.
     * @return array
     * FEEDBACK_RECEIVED
     * Feedback received
     */
    private function workflowStatusConf()
    {
        return [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'DRAFT',
                'workflow_id' => self::WORKFLOW_NAME,
                'label' => 'Draft',
                'sort_order' => '0'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'ACTIVE',
                'workflow_id' => self::WORKFLOW_NAME,
                'label' => 'Active',
                'sort_order' => '1'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'TOVALIDATE',
                'workflow_id' => self::WORKFLOW_NAME,
                'label' => 'To validate',
                'sort_order' => '2'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'RELEVANT',
                'workflow_id' => self::WORKFLOW_NAME,
                'label' => 'Relevant',
                'sort_order' => '3'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'REJECTED',
                'workflow_id' => self::WORKFLOW_NAME,
                'label' => 'Rejected',
                'sort_order' => '4'
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
                'start_status_id' => 'DRAFT',
                'end_status_id' => 'ACTIVE'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'ACTIVE',
                'end_status_id' => 'DRAFT'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'ACTIVE',
                'end_status_id' => 'TOVALIDATE'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'TOVALIDATE',
                'end_status_id' => 'RELEVANT'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'TOVALIDATE',
                'end_status_id' => 'REJECTED'
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
            // "Draft" status
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'DRAFT',
                'key' => 'class',
                'value' => 'btn btn-navigation-primary'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'DRAFT',
                'key' => 'description',
                'value' => 'Expression of interest in editing'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'DRAFT',
                'key' => 'label',
                'value' => 'Draft'
            ],
            
            // "Active" status
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'ACTIVE',
                'key' => 'class',
                'value' => 'btn btn-navigation-primary'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'ACTIVE',
                'key' => 'description',
                'value' => 'It puts an expression of interest in active status'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'ACTIVE',
                'key' => 'label',
                'value' => 'Active'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'ACTIVE',
                'key' => 'message',
                'value' => 'Do you want to set the active status?'
            ],
            
            // "To validate" status
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'TOVALIDATE',
                'key' => 'class',
                'value' => 'btn btn-navigation-primary'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'TOVALIDATE',
                'key' => 'description',
                'value' => 'It puts an expression of interest in to validate status'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'TOVALIDATE',
                'key' => 'label',
                'value' => 'Validation request'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'TOVALIDATE',
                'key' => 'message',
                'value' => 'Do you want to set the validation request status?'
            ],
            
            // "Relevant" status
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'RELEVANT',
                'key' => 'class',
                'value' => 'btn btn-navigation-primary'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'RELEVANT',
                'key' => 'description',
                'value' => 'The expression of interest will be put in the relevant status'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'RELEVANT',
                'key' => 'label',
                'value' => 'Relevant'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'RELEVANT',
                'key' => 'message',
                'value' => 'Do you want to put the expression of interest in relevant status?'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'RELEVANT',
                'key' => 'order',
                'value' => '1'
            ],
            
            // "Rejected" status
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'REJECTED',
                'key' => 'class',
                'value' => 'btn btn-navigation-primary'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'REJECTED',
                'key' => 'description',
                'value' => 'The expression of interest will be rejected'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'REJECTED',
                'key' => 'label',
                'value' => 'Rejected'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'REJECTED',
                'key' => 'message',
                'value' => 'Do you want to reject the expression of interest?'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'REJECTED',
                'key' => 'order',
                'value' => '1'
            ]
        ];
    }
}
