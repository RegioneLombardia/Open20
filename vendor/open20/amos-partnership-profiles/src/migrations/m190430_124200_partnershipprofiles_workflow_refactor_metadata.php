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
 * Class m190430_124200_partnershipprofiles_workflow_refactor_metadata
 */
class m190430_124200_partnershipprofiles_workflow_refactor_metadata extends AmosMigrationWorkflow
{
    const WORKFLOW_NAME = 'PartnershipProfilesWorkflow';
    const WORKFLOW_CLOSED = 'CLOSED';

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
                'status_id' => self::WORKFLOW_CLOSED,
                'key' => 'label',
                'value' => 'Close'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'remove' => true,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_CLOSED,
                'key' => 'description',
                'value' => 'The partnership profile will be closed'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_CLOSED,
                'key' => 'label',
                'value' => '#'.self::WORKFLOW_NAME.'_'.self::WORKFLOW_CLOSED.'_label'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_CLOSED,
                'key' => 'description',
                'value' => '#'.self::WORKFLOW_NAME.'_'.self::WORKFLOW_CLOSED.'_description'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_CLOSED,
                'key' => 'buttonLabel',
                'value' => '#'.self::WORKFLOW_NAME.'_'.self::WORKFLOW_CLOSED.'_buttonLabel'
            ],
        ];
    }
}

