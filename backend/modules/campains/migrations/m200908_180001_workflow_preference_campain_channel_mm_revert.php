<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\results\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWorkflow;


/**
 * Class m200908_180001_workflow_preference_campain_channel_mm_revert
 */
class m200908_180001_workflow_preference_campain_channel_mm_revert extends AmosMigrationWorkflow
{

    const PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW = 'PreferenceCampainChannelMmWorkflow';
    
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
        return \yii\helpers\ArrayHelper::merge(
            parent::setWorkflow(),
            $this->workflowConf(),
            $this->workflowStatusConf(),
            $this->workflowTransitionsConf(),
            $this->workflowMetadataConf()
        );
    }

    
	/**
     * In this method there are the new workflow configuration.
     * @return array
     */
    private function workflowConf()
    {
        return [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW,
                'id' => self::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW,
                'initial_status_id' => 'TOBEAPPROVED'
            ],
        ];
    }
    

    /**
     * In this method there are the new workflow statuses configurations.
     * @return array
     */
    private function workflowStatusConf()
    {
        return [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'TOBEAPPROVED',
                'workflow_id' => self::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW,
                'label' => 'To Be Approved',
                'sort_order' => '0'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'APPROVED',
                'workflow_id' => self::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW,
                'label' => 'Approved',
                'sort_order' => '1'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'SENDINGINPROGRESS',
                'workflow_id' => self::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW,
                'label' => 'Sending in Progress',
                'sort_order' => '2'
			],
			[
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'SENT',
                'workflow_id' => self::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW,
                'label' => 'Sent',
                'sort_order' => '3'
            ],

        ];
    }
    

    /**
     * In this method there are the new workflow status transitions configurations.
     * @return array
     */
    private function workflowTransitionsConf()
    {
        return [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW,
                'start_status_id' => 'TOBEAPPROVED',
                'end_status_id' => 'APPROVED'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW,
                'start_status_id' => 'APPROVED',
                'end_status_id' => 'SENDINGINPROGRESS'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW,
                'start_status_id' => 'SENDINGINPROGRESS',
                'end_status_id' => 'SENT'
            ],
        ];
    }
    
    
    /**
     * In this method there are the new workflow metadata configurations.
     * @return array
     */
    private function workflowMetadataConf()
    {
        return [

            // "TOBEAPPROVED" status
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW,
                'status_id' => 'TOBEAPPROVED',
                'key' => 'description',
                'value' => 'To Be Approved'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW,
                'status_id' => 'TOBEAPPROVED',
                'key' => 'buttonLabel',
                'value' => 'To Be Approved'
            ],


            // "APPROVED" status
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW,
                'status_id' => 'APPROVED',
                'key' => 'description',
                'value' => 'Approved'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW,
                'status_id' => 'APPROVED',
                'key' => 'buttonLabel',
                'value' => 'Approved'
            ],


            // "SENDINGINPROGRESS" status
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW,
                'status_id' => 'SENDINGINPROGRESS',
                'key' => 'description',
                'value' => 'Sending In Progres'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW,
                'status_id' => 'SENDINGINPROGRESS',
                'key' => 'buttonLabel',
                'value' => 'Sending In Progres'
			],


			// "SENT" status
			[
				'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
				'workflow_id' => self::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW,
				'status_id' => 'SENT',
				'key' => 'description',
				'value' => 'Sent'
			],
			[
				'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
				'workflow_id' => self::PREFERENCE_CAMPAIN_CHANNEL_MM_WORKFLOW,
				'status_id' => 'SENT',
				'key' => 'buttonLabel',
				'value' => 'Sent'
			],

        ];
    }
    
}
	