<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWorkflow;

class m180522_135341_documenti_workflow_refactor_metadata extends AmosMigrationWorkflow
{

    // PER OGNI WORKFLOW ID CONST
    const WORKFLOW_NAME = 'DocumentiWorkflow';
    const WORKFLOW_DRAFT = 'BOZZA';
    const WORKFLOW_TOVALIDATE = 'DAVALIDARE';
    const WORKFLOW_VALIDATED = 'VALIDATO';

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
            // DOCUMENTI WORKFLOW
            // "DRAFT" status
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_DRAFT,
                'key' => 'buttonLabel',
                'value' => '#'.self::WORKFLOW_DRAFT.'_buttonLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_DRAFT,
                'key' => 'description',
                'value' => '#'.self::WORKFLOW_DRAFT.'_description'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_DRAFT,
                'key' => 'label',
                'value' => '#'.self::WORKFLOW_DRAFT.'_label'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_DRAFT,
                'key' => self::WORKFLOW_TOVALIDATE.'_buttonLabel',
                'value' => '#'.self::WORKFLOW_DRAFT.'_'.self::WORKFLOW_TOVALIDATE.'_buttonLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_DRAFT,
                'key' => self::WORKFLOW_TOVALIDATE.'_description',
                'value' => '#'.self::WORKFLOW_DRAFT.'_'.self::WORKFLOW_TOVALIDATE.'_description'
            ],
            // TOVALIDATE
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_TOVALIDATE,
                'key' => 'buttonLabel',
                'value' => '#'.self::WORKFLOW_TOVALIDATE.'_buttonLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_TOVALIDATE,
                'key' => 'description',
                'value' => '#'.self::WORKFLOW_TOVALIDATE.'_description'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_TOVALIDATE,
                'key' => 'label',
                'value' => '#'.self::WORKFLOW_TOVALIDATE.'_label'
            ],
            // VALIDATED
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_VALIDATED,
                'key' => 'buttonLabel',
                'value' => '#'.self::WORKFLOW_VALIDATED.'_buttonLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_VALIDATED,
                'key' => 'description',
                'value' => '#'.self::WORKFLOW_VALIDATED.'_description'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_VALIDATED,
                'key' => 'label',
                'value' => '#'.self::WORKFLOW_VALIDATED.'_label'
            ],
            // -----------------------------------------------------------
        ];
    }
}
