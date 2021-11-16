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
 * Class m200826_112800_workflow_preference_campain
 */
class m200826_112800_workflow_preference_campain extends AmosMigrationWorkflow
{

	const PREFERENCE_CAMPAIN_WORKFLOW = 'PreferenceCampainWorkflow';

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
                'id' => self::PREFERENCE_CAMPAIN_WORKFLOW,
                'initial_status_id' => 'INMODIFICATION'
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
                'id' => 'INMODIFICATION',
                'workflow_id' => self::PREFERENCE_CAMPAIN_WORKFLOW,
                'label' => 'In Modification',
                'sort_order' => '0'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'TOSEND',
                'workflow_id' => self::PREFERENCE_CAMPAIN_WORKFLOW,
                'label' => 'To Send',
                'sort_order' => '1'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'SENDINGINPROGRESS',
                'workflow_id' => self::PREFERENCE_CAMPAIN_WORKFLOW,
                'label' => 'Sending in Progress',
                'sort_order' => '2'
			],
			[
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'SENT',
                'workflow_id' => self::PREFERENCE_CAMPAIN_WORKFLOW,
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
                'workflow_id' => self::PREFERENCE_CAMPAIN_WORKFLOW,
                'start_status_id' => 'INMODIFICATION',
                'end_status_id' => 'TOSEND'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::PREFERENCE_CAMPAIN_WORKFLOW,
                'start_status_id' => 'TOSEND',
                'end_status_id' => 'SENDINGINPROGRESS'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::PREFERENCE_CAMPAIN_WORKFLOW,
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

            // "INMODIFICATION" status
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::PREFERENCE_CAMPAIN_WORKFLOW,
                'status_id' => 'INMODIFICATION',
                'key' => 'description',
                'value' => 'In Modification'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::PREFERENCE_CAMPAIN_WORKFLOW,
                'status_id' => 'INMODIFICATION',
                'key' => 'buttonLabel',
                'value' => 'In Modification'
            ],


            // "TOSEND" status
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::PREFERENCE_CAMPAIN_WORKFLOW,
                'status_id' => 'TOSEND',
                'key' => 'description',
                'value' => 'To Send'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::PREFERENCE_CAMPAIN_WORKFLOW,
                'status_id' => 'TOSEND',
                'key' => 'buttonLabel',
                'value' => 'To Send'
            ],


            // "SENDINGINPROGRESS" status
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::PREFERENCE_CAMPAIN_WORKFLOW,
                'status_id' => 'SENDINGINPROGRESS',
                'key' => 'description',
                'value' => 'Sending In Progres'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::PREFERENCE_CAMPAIN_WORKFLOW,
                'status_id' => 'SENDINGINPROGRESS',
                'key' => 'buttonLabel',
                'value' => 'Sending In Progres'
			],


			// "SENT" status
			[
				'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
				'workflow_id' => self::PREFERENCE_CAMPAIN_WORKFLOW,
				'status_id' => 'SENT',
				'key' => 'description',
				'value' => 'Sent'
			],
			[
				'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
				'workflow_id' => self::PREFERENCE_CAMPAIN_WORKFLOW,
				'status_id' => 'SENT',
				'key' => 'buttonLabel',
				'value' => 'Sent'
			],

        ];
    }

}

