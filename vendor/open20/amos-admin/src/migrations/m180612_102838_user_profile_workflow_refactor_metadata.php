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

/**
 * Class m180612_102838_user_profile_workflow_refactor_metadata
 */
class m180612_102838_user_profile_workflow_refactor_metadata extends AmosMigrationWorkflow
{

    // PER OGNI WORKFLOW ID CONST
    const WORKFLOW_NAME = 'UserProfileWorkflow';
    const WORKFLOW_DRAFT = 'DRAFT';
    const WORKFLOW_TOVALIDATE = 'TOVALIDATE';
    const WORKFLOW_VALIDATED = 'VALIDATED';
    const WORKFLOW_NOTVALIDATED = 'NOTVALIDATED';

    /**
     * @inheritdoc
     */
    protected function setWorkflow()
    {

        return [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_TOVALIDATE,
                'key' => 'message',
                'value' => '#' . self::WORKFLOW_TOVALIDATE . '_message'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_VALIDATED,
                'key' => 'message',
                'value' => '#' . self::WORKFLOW_VALIDATED . '_message'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_TOVALIDATE,
                'key' => self::WORKFLOW_VALIDATED.'_description',
                'value' => '#' . self::WORKFLOW_TOVALIDATE . '_' . self::WORKFLOW_VALIDATED . '_description'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_NOTVALIDATED,
                'key' => self::WORKFLOW_VALIDATED.'_buttonLabel',
                'value' => '#' . self::WORKFLOW_NOTVALIDATED . '_' . self::WORKFLOW_VALIDATED . '_buttonLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_NOTVALIDATED,
                'key' => self::WORKFLOW_VALIDATED.'_description',
                'value' => '#' . self::WORKFLOW_NOTVALIDATED . '_' . self::WORKFLOW_VALIDATED . '_description'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_NOTVALIDATED,
                'key' => 'message',
                'value' => '#' . self::WORKFLOW_NOTVALIDATED . '_message'
            ],
        ];
    }
}
