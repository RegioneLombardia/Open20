<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\notify\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWorkflow;

/**
 * Class m201204_120220_fix_newsletter_workflow
 */
class m201204_120220_fix_newsletter_workflow extends AmosMigrationWorkflow
{
    const WORKFLOW_NAME = 'NewsletterWorkflow';
    
    /**
     * @inheritdoc
     */
    protected function setWorkflow()
    {
        return [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'WAITRESEND',
                'end_status_id' => 'SENT',
                'remove' => true
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'WAITRESEND',
                'end_status_id' => 'SENDING'
            ]
        ];
    }
}
