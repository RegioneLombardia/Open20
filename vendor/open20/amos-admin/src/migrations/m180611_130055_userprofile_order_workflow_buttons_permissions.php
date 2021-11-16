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
use \open20\amos\admin\models\UserProfile;
use yii\helpers\ArrayHelper;


/**
 * Class m180611_130055_userprofile_order_workflow_buttons_permissions
 */
class m180611_130055_userprofile_order_workflow_buttons_permissions extends AmosMigrationWorkflow
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
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => UserProfile::USERPROFILE_WORKFLOW,
                'status_id' => 'VALIDATED',
                'key' => 'order',
                'value' => '4'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => UserProfile::USERPROFILE_WORKFLOW,
                'status_id' => 'NOTVALIDATED',
                'key' => 'order',
                'value' => '3'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => UserProfile::USERPROFILE_WORKFLOW,
                'status_id' => 'DRAFT',
                'key' => 'order',
                'value' => '1'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => UserProfile::USERPROFILE_WORKFLOW,
                'status_id' => 'TOVALIDATE',
                'key' => 'order',
                'value' => '2'
            ],
        ]);
    }
}
