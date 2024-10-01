<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWorkflow;
use open20\amos\organizzazioni\models\Profilo;

/**
 * Class m210122_151721_add_transition_profilo_workflow
 */
class m210122_151721_add_transition_profilo_workflow extends AmosMigrationWorkflow
{
    /**
     * @inheritdoc
     */
    protected function setWorkflow()
    {
        return [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => Profilo::PROFILO_WORKFLOW,
                'start_status_id' => 'DRAFT',
                'end_status_id' => 'VALIDATED'
            ]
        ];
    }
}
