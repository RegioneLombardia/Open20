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
use open20\amos\discussioni\models\DiscussioniTopic;

/**
 * Class m170428_164712_change_news_workflow
 */
class m180605_095712_add_transition_discussioni_workflow extends AmosMigrationWorkflow
{
    /**
     * @inheritdoc
     */
    protected function setWorkflow()
    {
        return [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => DiscussioniTopic::DISCUSSIONI_WORKFLOW,
                'start_status_id' => 'BOZZA',
                'end_status_id' => 'ATTIVA'
            ]
        ];
    }
}
