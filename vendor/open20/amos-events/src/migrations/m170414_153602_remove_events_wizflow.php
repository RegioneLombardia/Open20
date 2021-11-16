<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWorkflow;
use yii\helpers\ArrayHelper;

/**
 * Class m170414_153602_remove_events_wizflow
 */
class m170414_153602_remove_events_wizflow extends AmosMigrationWorkflow
{
    const WIZARD_WORKFLOW_NAME = 'EventCreationWizardWorkflow';

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
        return ArrayHelper::merge(
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
                'id' => self::WIZARD_WORKFLOW_NAME,
                'initial_status_id' => 'INTRODUCTION'
            ]
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
                'id' => 'INTRODUCTION',
                'workflow_id' => self::WIZARD_WORKFLOW_NAME,
                'label' => 'Introduction',
                'sort_order' => '0'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'DESCRIPTION',
                'workflow_id' => self::WIZARD_WORKFLOW_NAME,
                'label' => 'Description',
                'sort_order' => '1'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'ORGANIZATIONALDATA',
                'workflow_id' => self::WIZARD_WORKFLOW_NAME,
                'label' => 'Organizational data',
                'sort_order' => '2'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'PUBLICATION',
                'workflow_id' => self::WIZARD_WORKFLOW_NAME,
                'label' => 'Publication',
                'sort_order' => '3'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'SUMMARY',
                'workflow_id' => self::WIZARD_WORKFLOW_NAME,
                'label' => 'Summary',
                'sort_order' => '4'
            ]
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
                'workflow_id' => self::WIZARD_WORKFLOW_NAME,
                'start_status_id' => 'INTRODUCTION',
                'end_status_id' => 'DESCRIPTION'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WIZARD_WORKFLOW_NAME,
                'start_status_id' => 'DESCRIPTION',
                'end_status_id' => 'ORGANIZATIONALDATA'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WIZARD_WORKFLOW_NAME,
                'start_status_id' => 'ORGANIZATIONALDATA',
                'end_status_id' => 'PUBLICATION'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WIZARD_WORKFLOW_NAME,
                'start_status_id' => 'PUBLICATION',
                'end_status_id' => 'SUMMARY'
            ]
        ];
    }

    /**
     * In this method there are the new workflow metadata configurations.
     * @return array
     */
    private function workflowMetadataConf()
    {
        return [
            // Metadata for "Introduction" step
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WIZARD_WORKFLOW_NAME,
                'status_id' => 'INTRODUCTION',
                'key' => 'model',
                'value' => 'open20\amos\events\models\CreationEventWizardIntroduction'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WIZARD_WORKFLOW_NAME,
                'status_id' => 'INTRODUCTION',
                'key' => 'view',
                'value' => 'introduction'
            ],

            // Metadata for "Description" step
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WIZARD_WORKFLOW_NAME,
                'status_id' => 'DESCRIPTION',
                'key' => 'model',
                'value' => 'open20\amos\events\models\CreationEventWizardDescription'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WIZARD_WORKFLOW_NAME,
                'status_id' => 'DESCRIPTION',
                'key' => 'view',
                'value' => 'description'
            ],

            // Metadata for "Organizational data" step
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WIZARD_WORKFLOW_NAME,
                'status_id' => 'ORGANIZATIONALDATA',
                'key' => 'model',
                'value' => 'open20\amos\events\models\CreationEventWizardOrganizationalData'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WIZARD_WORKFLOW_NAME,
                'status_id' => 'ORGANIZATIONALDATA',
                'key' => 'view',
                'value' => 'organizationaldata'
            ],

            // Metadata for "Publication" step
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WIZARD_WORKFLOW_NAME,
                'status_id' => 'PUBLICATION',
                'key' => 'model',
                'value' => 'open20\amos\events\models\CreationEventWizardPublication'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WIZARD_WORKFLOW_NAME,
                'status_id' => 'PUBLICATION',
                'key' => 'view',
                'value' => 'publication'
            ],

            // Metadata for "Summary" step
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WIZARD_WORKFLOW_NAME,
                'status_id' => 'SUMMARY',
                'key' => 'model',
                'value' => 'open20\amos\events\models\CreationEventWizardSummary'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WIZARD_WORKFLOW_NAME,
                'status_id' => 'SUMMARY',
                'key' => 'view',
                'value' => 'summary'
            ]
        ];
    }
}
