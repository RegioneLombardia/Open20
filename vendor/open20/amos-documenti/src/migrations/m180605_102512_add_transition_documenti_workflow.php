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
use open20\amos\documenti\models\Documenti;

/**
 * Class m170428_164712_change_news_workflow
 */
class m180605_102512_add_transition_documenti_workflow extends AmosMigrationWorkflow
{
    /**
     * @inheritdoc
     */
    protected function setWorkflow()
    {
        return [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => Documenti::DOCUMENTI_WORKFLOW,
                'start_status_id' => 'BOZZA',
                'end_status_id' => 'VALIDATO'
            ]
        ];
    }
}
