<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\projectmanagement\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWorkflow;

/**
 * Class m190522_155100_expressionofinterest_workflow_refactor_metadata
 */
class m190522_155100_expressionofinterest_workflow_refactor_metadata extends AmosMigrationWorkflow
{
    const WORKFLOW_NAME = 'ExpressionsOfInterestWorkflow';

    /**
     * @inheritdoc
     */
    protected function setWorkflow()
    {
        return [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'remove' => true,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'ACTIVE',
                'key' => 'label',
                'value' => 'Active'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'ACTIVE',
                'key' => 'label',
                'value' => '#'.self::WORKFLOW_NAME.'_ACTIVE_label'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'remove' => true,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'DRAFT',
                'key' => 'label',
                'value' => 'Draft'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'DRAFT',
                'key' => 'label',
                'value' => '#'.self::WORKFLOW_NAME.'_DRAFT_label'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'remove' => true,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'REJECTED',
                'key' => 'label',
                'value' => 'Rejected'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'REJECTED',
                'key' => 'label',
                'value' => '#'.self::WORKFLOW_NAME.'_REJECTED_label'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'remove' => true,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'RELEVANT',
                'key' => 'label',
                'value' => 'Relevant'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'RELEVANT',
                'key' => 'label',
                'value' => '#'.self::WORKFLOW_NAME.'_RELEVANT_label'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'remove' => true,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'TOVALIDATE',
                'key' => 'label',
                'value' => 'In evaluation'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'TOVALIDATE',
                'key' => 'label',
                'value' => '#'.self::WORKFLOW_NAME.'_TOVALIDATE_label'
            ],
        ];
    }
}