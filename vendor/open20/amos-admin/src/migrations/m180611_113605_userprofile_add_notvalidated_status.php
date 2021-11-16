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
 * Class m180611_113605_userprofile_add_notvalidated_status
 */
class m180611_113605_userprofile_add_notvalidated_status extends AmosMigrationWorkflow
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    protected function setWorkflow()
    {
        return ArrayHelper::merge(parent::setWorkflow(), [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'workflow_id' => UserProfile::USERPROFILE_WORKFLOW,
                'id' => 'NOTVALIDATED',
                'label' => 'Not validated',
                'sort_order' => '4'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => UserProfile::USERPROFILE_WORKFLOW,
                'start_status_id' => 'VALIDATED',
                'end_status_id' => 'NOTVALIDATED'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => UserProfile::USERPROFILE_WORKFLOW,
                'start_status_id' => 'TOVALIDATE',
                'end_status_id' => 'NOTVALIDATED'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => UserProfile::USERPROFILE_WORKFLOW,
                'start_status_id' => 'NOTVALIDATED',
                'end_status_id' => 'TOVALIDATE'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => UserProfile::USERPROFILE_WORKFLOW,
                'start_status_id' => 'NOTVALIDATED',
                'end_status_id' => 'VALIDATED'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => UserProfile::USERPROFILE_WORKFLOW,
                'status_id' => 'NOTVALIDATED',
                'key' => 'buttonLabel',
                'value' => '#NOTVALIDATED_buttonLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => UserProfile::USERPROFILE_WORKFLOW,
                'status_id' => 'NOTVALIDATED',
                'key' => 'description',
                'value' => '#NOTVALIDATED_description'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => UserProfile::USERPROFILE_WORKFLOW,
                'status_id' => 'NOTVALIDATED',
                'key' => 'label',
                'value' => '#NOTVALIDATED_label'
            ]
        ]);
    }
}
