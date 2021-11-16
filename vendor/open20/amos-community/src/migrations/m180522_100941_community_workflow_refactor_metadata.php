<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\projectmanagement\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWorkflow;

class m180522_100941_community_workflow_refactor_metadata extends AmosMigrationWorkflow
{

    // PER OGNI WORKFLOW ID CONST
    const WORKFLOW_NAME = 'CommunityWorkflow';

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
            // COMMUNITY WORKFLOW
            // "DRAFT" status
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'DRAFT',
                'key' => 'buttonLabel',
                'value' => '#draft_buttonLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'DRAFT',
                'key' => 'description',
                'value' => '#draft_description'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'DRAFT',
                'key' => 'label',
                'value' => '#draft_label'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'DRAFT',
                'key' => 'TOVALIDATE_buttonLabel',
                'value' => '#draft_TOVALIDATE_buttonLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'DRAFT',
                'key' => 'TOVALIDATE_description',
                'value' => '#draft_TOVALIDATE_description'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'DRAFT',
                'key' => 'VALIDATED_buttonLabel',
                'value' => '#draft_VALIDATED_buttonLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'DRAFT',
                'key' => 'VALIDATED_description',
                'value' => '#draft_VALIDATED_description'
            ],
            // TOVALIDATE
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'TOVALIDATE',
                'key' => 'buttonLabel',
                'value' => '#tovalidate_buttonLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'TOVALIDATE',
                'key' => 'description',
                'value' => '#tovalidate_description'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'TOVALIDATE',
                'key' => 'label',
                'value' => '#tovalidate_label'
            ],
            // VALIDATED
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'VALIDATED',
                'key' => 'buttonLabel',
                'value' => '#validated_buttonLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'VALIDATED',
                'key' => 'description',
                'value' => '#validated_description'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'VALIDATED',
                'key' => 'label',
                'value' => '#validated_label'
            ],
            // -----------------------------------------------------------
        ];
    }
}
