<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use cornernote\workflow\manager\models\Metadata;
use open20\amos\admin\AmosAdmin;
use open20\amos\core\migration\AmosMigrationWorkflow;
use open20\amos\core\migration\libs\common\MigrationCommon;
use yii\helpers\ArrayHelper;

/**
 * Class m170629_083747_delete_old_user_profile_workflow
 */
class m170629_083747_delete_old_user_profile_workflow extends AmosMigrationWorkflow
{
    const WORKFLOW_NAME = 'UserProfileWorkflow';
    
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
    protected function beforeRemoveConfs()
    {
        try {
            $this->delete(Metadata::tableName(), ['workflow_id' => 'UserProfileWorkflow']);
        } catch (\Exception $exception) {
            MigrationCommon::printConsoleMessage(AmosAdmin::t('amosadmin', 'Error while deleting ' . self::WORKFLOW_NAME . ' metadata'));
            return false;
        }
        return true;
    }
    
    /**
     * @inheritdoc
     */
    protected function setWorkflow()
    {
        return ArrayHelper::merge(
            $this->workflowConf(),
            $this->workflowStatusConf(),
            $this->workflowTransitionsConf()
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
                'initial_status_id' => 'MODIFICAINCORSO'
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
                'id' => 'DISATTIVO',
                'workflow_id' => self::WORKFLOW_NAME,
                'label' => 'Disattivo',
                'sort_order' => '1',
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'MODIFICAINCORSO',
                'workflow_id' => self::WORKFLOW_NAME,
                'label' => 'Modifica in corso',
                'sort_order' => '2'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'ATTIVO',
                'workflow_id' => self::WORKFLOW_NAME,
                'label' => 'Attivo',
                'sort_order' => '3',
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'ATTIVOCONRICHIESTAVALIDAZIONE',
                'workflow_id' => self::WORKFLOW_NAME,
                'label' => 'Attivo con richiesta validazione',
                'sort_order' => '4'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'ATTIVOEVALIDATO',
                'workflow_id' => self::WORKFLOW_NAME,
                'label' => 'Attivo e validato',
                'sort_order' => '5'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'ATTIVONONVALIDATO',
                'workflow_id' => self::WORKFLOW_NAME,
                'label' => 'Attivo non validato',
                'sort_order' => '6',
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
                'start_status_id' => 'DISATTIVO',
                'end_status_id' => 'MODIFICAINCORSO'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'DISATTIVO',
                'end_status_id' => 'ATTIVO'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'DISATTIVO',
                'end_status_id' => 'ATTIVOCONRICHIESTAVALIDAZIONE'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'MODIFICAINCORSO',
                'end_status_id' => 'ATTIVO'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'MODIFICAINCORSO',
                'end_status_id' => 'ATTIVOCONRICHIESTAVALIDAZIONE'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'MODIFICAINCORSO',
                'end_status_id' => 'DISATTIVO'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'ATTIVO',
                'end_status_id' => 'MODIFICAINCORSO'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'ATTIVO',
                'end_status_id' => 'ATTIVOCONRICHIESTAVALIDAZIONE'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'ATTIVO',
                'end_status_id' => 'ATTIVOEVALIDATO'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'ATTIVO',
                'end_status_id' => 'DISATTIVO'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'ATTIVOCONRICHIESTAVALIDAZIONE',
                'end_status_id' => 'MODIFICAINCORSO'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'ATTIVOCONRICHIESTAVALIDAZIONE',
                'end_status_id' => 'ATTIVOEVALIDATO'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'ATTIVOCONRICHIESTAVALIDAZIONE',
                'end_status_id' => 'ATTIVO'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'ATTIVOCONRICHIESTAVALIDAZIONE',
                'end_status_id' => 'DISATTIVO'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'ATTIVOCONRICHIESTAVALIDAZIONE',
                'end_status_id' => 'ATTIVONONVALIDATO'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'ATTIVOEVALIDATO',
                'end_status_id' => 'MODIFICAINCORSO'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'ATTIVOEVALIDATO',
                'end_status_id' => 'ATTIVO'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'ATTIVOEVALIDATO',
                'end_status_id' => 'DISATTIVO'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'ATTIVONONVALIDATO',
                'end_status_id' => 'MODIFICAINCORSO'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'ATTIVONONVALIDATO',
                'end_status_id' => 'ATTIVOEVALIDATO'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => self::WORKFLOW_NAME,
                'start_status_id' => 'ATTIVONONVALIDATO',
                'end_status_id' => 'DISATTIVO'
            ],
        ];
    }
}
