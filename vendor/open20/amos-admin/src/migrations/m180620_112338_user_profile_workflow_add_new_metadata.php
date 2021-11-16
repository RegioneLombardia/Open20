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
 * Class m180620_112338_user_profile_workflow_add_new_metadata
 */
class m180620_112338_user_profile_workflow_add_new_metadata extends AmosMigrationWorkflow
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
                'status_id' => self::WORKFLOW_VALIDATED,
                'key' => 'simplifiedMessage',
                'value' => '#' . self::WORKFLOW_VALIDATED . '_simplifiedMessage'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_TOVALIDATE,
                'key' => 'simplifiedMessage',
                'value' => '#' . self::WORKFLOW_TOVALIDATE . '_simplifiedMessage'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_DRAFT,
                'key' => 'simplifiedMessage',
                'value' => '#' . self::WORKFLOW_DRAFT . '_simplifiedMessage'
            ],
        ];
    }
}
