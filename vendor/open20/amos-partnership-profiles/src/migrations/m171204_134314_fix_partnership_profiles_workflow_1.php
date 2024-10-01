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
 * Class m171204_134314_fix_partnership_profiles_workflow_1
 */
class m171204_134314_fix_partnership_profiles_workflow_1 extends AmosMigrationWorkflow
{
    const WORKFLOW_NAME = 'PartnershipProfilesWorkflow';

    /**
     * @inheritdoc
     */
    protected function setWorkflow()
    {
        return [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'FEEDBACKRECEIVED',
                'key' => 'hidden',
                'value' => 'true'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'ARCHIVED',
                'key' => 'hidden',
                'value' => 'true'
            ]
        ];
    }
}
