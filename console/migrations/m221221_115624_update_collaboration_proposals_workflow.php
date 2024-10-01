<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWorkflow;
use open20\amos\collaborations\models\CollaborationProposals;

/**
 * 
 */
class m221221_115624_update_collaboration_proposals_workflow extends AmosMigrationWorkflow
{
    /**
     * @inheritdoc
     */
    protected function setWorkflow()
    {
        return [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => CollaborationProposals::WORKFLOW,
                'start_status_id' => 'TOVALIDATE',
                'end_status_id' => 'DRAFT'
            ],
        ];
    }
}
