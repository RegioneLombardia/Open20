<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWorkflow;

/**
 * Class m180605_103843_admin_userprofile_add_validated_to_tovalidate_transition
 */
class m180605_103843_admin_userprofile_add_validated_to_tovalidate_transition extends AmosMigrationWorkflow
{
    const WORKFLOW_NAME = 'UserProfileWorkflow';
    
    /**
     * @inheritdoc
     */
    protected function setWorkflow()
    {
        return [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'VALIDATED',
                'end_status_id' => 'TOVALIDATE'
            ]
        ];
    }
}
