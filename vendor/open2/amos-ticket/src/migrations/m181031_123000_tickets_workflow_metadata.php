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

/**
 * Class m181031_123000_tickets_workflow_metadata
 */
class m181031_123000_tickets_workflow_metadata extends AmosMigrationWorkflow
{
    // PER OGNI WORKFLOW ID CONST
    const WORKFLOW_NAME = 'TicketWorkflow';
    const WORKFLOW_WAITING = 'WAITING';
    const WORKFLOW_PROCESSING = 'PROCESSING';
    const WORKFLOW_CLOSED = 'CLOSED';

    /**
     * @inheritdoc
     */
    protected function beforeAddConfs()
    {
        $this->delete('sw_metadata', 'workflow_id = "' . self::WORKFLOW_NAME . '"');
        return true;
    }

    /**
     * @inheritdoc
     */
    protected function setWorkflow()
    {
        return [
            // "DRAFT" status
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_WAITING,
                'key' => 'buttonLabel',
                'value' => '#' . self::WORKFLOW_WAITING . '_buttonLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_WAITING,
                'key' => 'description',
                'value' => '#' . self::WORKFLOW_WAITING . '_description'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_WAITING,
                'key' => 'label',
                'value' => '#' . self::WORKFLOW_WAITING . '_label'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_WAITING,
                'key' => self::WORKFLOW_PROCESSING . '_buttonLabel',
                'value' => '#' . self::WORKFLOW_WAITING . '_' . self::WORKFLOW_PROCESSING . '_buttonLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_WAITING,
                'key' => self::WORKFLOW_PROCESSING . '_description',
                'value' => '#' . self::WORKFLOW_WAITING . '_' . self::WORKFLOW_PROCESSING . '_description'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_WAITING,
                'key' => self::WORKFLOW_CLOSED . '_buttonLabel',
                'value' => '#' . self::WORKFLOW_WAITING . '_' . self::WORKFLOW_CLOSED . '_buttonLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_WAITING,
                'key' => self::WORKFLOW_CLOSED . '_description',
                'value' => '#' . self::WORKFLOW_WAITING . '_' . self::WORKFLOW_CLOSED . '_description'
            ],
            // TOVALIDATE
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_PROCESSING,
                'key' => 'buttonLabel',
                'value' => '#' . self::WORKFLOW_PROCESSING . '_buttonLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_PROCESSING,
                'key' => 'description',
                'value' => '#' . self::WORKFLOW_PROCESSING . '_description'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_PROCESSING,
                'key' => 'label',
                'value' => '#' . self::WORKFLOW_PROCESSING . '_label'
            ],
            // VALIDATED
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_CLOSED,
                'key' => 'buttonLabel',
                'value' => '#' . self::WORKFLOW_CLOSED . '_buttonLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_CLOSED,
                'key' => 'description',
                'value' => '#' . self::WORKFLOW_CLOSED . '_description'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_CLOSED,
                'key' => 'label',
                'value' => '#' . self::WORKFLOW_CLOSED . '_label'
            ],
            // -----------------------------------------------------------
        ];
    }
}
