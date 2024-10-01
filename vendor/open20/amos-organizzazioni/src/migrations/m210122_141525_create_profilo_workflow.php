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
use yii\helpers\ArrayHelper;

/**
 * Class m210122_141525_create_profilo_workflow
 */
class m210122_141525_create_profilo_workflow extends AmosMigrationWorkflow
{
    const WORKFLOW_NAME = 'ProfiloWorkflow';
    const WORKFLOW_DRAFT = 'DRAFT';
    const WORKFLOW_TOVALIDATE = 'TOVALIDATE';
    const WORKFLOW_VALIDATED = 'VALIDATED';

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
                'id' => self::WORKFLOW_NAME,
                'initial_status_id' => 'DRAFT'
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
                'id' => 'DRAFT',
                'workflow_id' => self::WORKFLOW_NAME,
                'label' => 'Draft',
                'sort_order' => '0'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'TOVALIDATE',
                'workflow_id' => self::WORKFLOW_NAME,
                'label' => 'To Validate',
                'sort_order' => '1'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'VALIDATED',
                'workflow_id' => self::WORKFLOW_NAME,
                'label' => 'Validated',
                'sort_order' => '2'
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
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'DRAFT',
                'end_status_id' => 'TOVALIDATE'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'TOVALIDATE',
                'end_status_id' => 'DRAFT'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'TOVALIDATE',
                'end_status_id' => 'VALIDATED'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'VALIDATED',
                'end_status_id' => 'DRAFT'
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

            // "Draft" status
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_DRAFT,
                'key' => 'label',
                'value' => '#'.self::WORKFLOW_DRAFT.'_label'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_DRAFT,
                'key' => 'buttonLabel',
                'value' => '#'.self::WORKFLOW_DRAFT.'_buttonLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_DRAFT,
                'key' => 'description',
                'value' => '#'.self::WORKFLOW_DRAFT.'_description'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_DRAFT,
                'key' => self::WORKFLOW_TOVALIDATE.'_buttonLabel',
                'value' => '#'.self::WORKFLOW_DRAFT.'_'.self::WORKFLOW_TOVALIDATE.'_buttonLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_DRAFT,
                'key' => self::WORKFLOW_TOVALIDATE.'_description',
                'value' => '#'.self::WORKFLOW_DRAFT.'_'.self::WORKFLOW_TOVALIDATE.'_description'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_DRAFT,
                'key' => self::WORKFLOW_VALIDATED.'_buttonLabel',
                'value' => '#'.self::WORKFLOW_DRAFT.'_'.self::WORKFLOW_VALIDATED.'_buttonLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_DRAFT,
                'key' => self::WORKFLOW_VALIDATED.'_description',
                'value' => '#'.self::WORKFLOW_DRAFT.'_'.self::WORKFLOW_VALIDATED.'_description'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_DRAFT,
                'key' => 'order',
                'value' => '1'
            ],

            // "To validate" status
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_TOVALIDATE,
                'key' => 'label',
                'value' => '#'.self::WORKFLOW_TOVALIDATE.'_label'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_TOVALIDATE,
                'key' => 'buttonLabel',
                'value' => '#'.self::WORKFLOW_TOVALIDATE.'_buttonLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_TOVALIDATE,
                'key' => 'description',
                'value' => '#'.self::WORKFLOW_TOVALIDATE.'_description'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_TOVALIDATE,
                'key' => 'order',
                'value' => '2'
            ],

            // "Validated" status
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_VALIDATED,
                'key' => 'label',
                'value' => '#'.self::WORKFLOW_VALIDATED.'_label'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_VALIDATED,
                'key' => 'buttonLabel',
                'value' => '#'.self::WORKFLOW_VALIDATED.'_buttonLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_VALIDATED,
                'key' => 'description',
                'value' => '#'.self::WORKFLOW_VALIDATED.'_description'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_VALIDATED,
                'key' => 'order',
                'value' => '3'
            ]
        ];
    }
}
