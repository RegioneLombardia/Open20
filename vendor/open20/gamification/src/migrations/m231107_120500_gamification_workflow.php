<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWorkflow;
use yii\helpers\ArrayHelper;

/**
 * Class m230421_121900_ordini_workflow
 */
class m231107_120500_gamification_workflow extends AmosMigrationWorkflow
{
    const GAMIFICATION_WORKFLOW_NAME = 'GamificationWorkflow';

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
                'id' => self::GAMIFICATION_WORKFLOW_NAME,
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
                'workflow_id' => self::GAMIFICATION_WORKFLOW_NAME,
                'label' => 'Draft',
                'sort_order' => '0'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'OPENING',
                'workflow_id' => self::GAMIFICATION_WORKFLOW_NAME,
                'label' => 'Opening',
                'sort_order' => '1'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'CONCLUDED',
                'workflow_id' => self::GAMIFICATION_WORKFLOW_NAME,
                'label' => 'Concluded',
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
                'workflow_id' => self::GAMIFICATION_WORKFLOW_NAME,
                'start_status_id' => 'DRAFT',
                'end_status_id' => 'OPENING'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::GAMIFICATION_WORKFLOW_NAME,
                'start_status_id' => 'OPENING',
                'end_status_id' => 'CONCLUDED'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::GAMIFICATION_WORKFLOW_NAME,
                'start_status_id' => 'CONCLUDED',
                'end_status_id' => 'OPENING'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::GAMIFICATION_WORKFLOW_NAME,
                'start_status_id' => 'OPENING',
                'end_status_id' => 'DRAFT'
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
            // "Draft" status
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::GAMIFICATION_WORKFLOW_NAME,
                'status_id' => 'DRAFT',
                'key' => 'class',
                'value' => 'btn btn-navigation-primary'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::GAMIFICATION_WORKFLOW_NAME,
                'status_id' => 'DRAFT',
                'key' => 'description',
                'value' => 'Bozza'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::GAMIFICATION_WORKFLOW_NAME,
                'status_id' => 'DRAFT',
                'key' => 'label',
                'value' => 'Rimetti in bozza'
            ],

            // "OPENING" status
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::GAMIFICATION_WORKFLOW_NAME,
                'status_id' => 'OPENING',
                'key' => 'class',
                'value' => 'btn btn-navigation-primary'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::GAMIFICATION_WORKFLOW_NAME,
                'status_id' => 'OPENING',
                'key' => 'description',
                'value' => "La gamification verrà aperta"
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::GAMIFICATION_WORKFLOW_NAME,
                'status_id' => 'OPENING',
                'key' => 'label',
                'value' => 'Apri'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::GAMIFICATION_WORKFLOW_NAME,
                'status_id' => 'OPENING',
                'key' => 'message',
                'value' => "Vuoi aprire questa gamification?"
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::GAMIFICATION_WORKFLOW_NAME,
                'status_id' => 'OPENING',
                'key' => 'order',
                'value' => '1'
            ],

            // "CONCLUDED" status
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::GAMIFICATION_WORKFLOW_NAME,
                'status_id' => 'CONCLUDED',
                'key' => 'class',
                'value' => 'btn btn-navigation-primary'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::GAMIFICATION_WORKFLOW_NAME,
                'status_id' => 'CONCLUDED',
                'key' => 'description',
                'value' => "La gamification verrà chiusa"
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::GAMIFICATION_WORKFLOW_NAME,
                'status_id' => 'CONCLUDED',
                'key' => 'label',
                'value' => 'Chiudi'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::GAMIFICATION_WORKFLOW_NAME,
                'status_id' => 'CONCLUDED',
                'key' => 'message',
                'value' => "Vuoi chiudere questa gamification?"
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::GAMIFICATION_WORKFLOW_NAME,
                'status_id' => 'CONCLUDED',
                'key' => 'order',
                'value' => '2'
            ]
        ];
    }
}