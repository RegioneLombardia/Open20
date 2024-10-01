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

/**
 * Class m180117_152828_add_partnership_profiles_workflow_metadata_1
 */
class m180117_152828_add_partnership_profiles_workflow_metadata_1 extends AmosMigrationWorkflow
{
    const WORKFLOW_NAME = 'PartnershipProfilesWorkflow';

    /**
     * @inheritdoc
     */
    protected function setWorkflow()
    {
        return [
            // "Draft" status
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'DRAFT',
                'key' => 'buttonLabel',
                'value' => "Update partnership profile"
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'DRAFT',
                'key' => 'message',
                'value' => 'Do you want to change the partnership profile?'
            ],

            // "To validate" status
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'TOVALIDATE',
                'key' => 'buttonLabel',
                'value' => "Request publication"
            ],

            // "Validated" status
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'VALIDATED',
                'key' => 'buttonLabel',
                'value' => "Publish"
            ],
        ];
    }
}
