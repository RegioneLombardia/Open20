<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWorkflow;
use \open20\amos\admin\models\UserProfile;
use yii\helpers\ArrayHelper;

/**
 * Class m180607_161005_remove_userprofile_in_draft_transition
 */
class m180607_161005_remove_userprofile_in_draft_transition extends AmosMigrationWorkflow
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->setProcessInverted(true);
    }

    /**
     * @inheritdoc
     */
    protected function setWorkflow()
    {
        return ArrayHelper::merge(parent::setWorkflow(), [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => UserProfile::USERPROFILE_WORKFLOW,
                'start_status_id' => 'VALIDATED',
                'end_status_id' => 'DRAFT'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => UserProfile::USERPROFILE_WORKFLOW,
                'start_status_id' => 'TOVALIDATE',
                'end_status_id' => 'DRAFT'
            ],
        ]);
    }
}
