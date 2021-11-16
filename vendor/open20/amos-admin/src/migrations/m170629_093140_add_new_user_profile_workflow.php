<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\migration\AmosMigrationWorkflow;
use open20\amos\core\migration\libs\common\MigrationCommon;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;

/**
 * Class m170629_093140_add_new_user_profile_workflow
 */
class m170629_093140_add_new_user_profile_workflow extends AmosMigrationWorkflow
{
    const WORKFLOW_NAME = 'UserProfileWorkflow';
    
    private $oldToNewStatus = [
        'UserProfileWorkflow/DISATTIVO' => 'UserProfileWorkflow/DRAFT',
        'UserProfileWorkflow/MODIFICAINCORSO' => 'UserProfileWorkflow/DRAFT',
        'UserProfileWorkflow/ATTIVO' => 'UserProfileWorkflow/TOVALIDATE',
        'UserProfileWorkflow/ATTIVOCONRICHIESTAVALIDAZIONE' => 'UserProfileWorkflow/TOVALIDATE',
        'UserProfileWorkflow/ATTIVONONVALIDATO' => 'UserProfileWorkflow/TOVALIDATE',
        'UserProfileWorkflow/ATTIVOEVALIDATO' => 'UserProfileWorkflow/VALIDATED'
    ];
    
    /**
     * @inheritdoc
     */
    protected function afterAddConfs()
    {
        foreach ($this->oldToNewStatus as $oldStatus => $newStatus) {
            $setCondition = ['status' => $newStatus];
            if ($oldStatus == 'UserProfileWorkflow/DISATTIVO') {
                $setCondition['attivo'] = 0;
            }
            MigrationCommon::printConsoleMessage(AmosAdmin::t('amosadmin', "Updating UserProfile workflow status from $oldStatus to $newStatus"));
            try {
                $this->update(UserProfile::tableName(), $setCondition, ['status' => $oldStatus]);
            } catch (\Exception $exception) {
                MigrationCommon::printConsoleMessage(AmosAdmin::t('amosadmin', "Error while updating UserProfile workflow status from $oldStatus to $newStatus"));
                return false;
            }
        }
        return true;
    }
    
    /**
     * @inheritdoc
     */
    protected function beforeRemoveConfs()
    {
        $confirm = Console::confirm(AmosAdmin::t('amosadmin', "ATTENZIONE!\nVerranno ripristinati i vecchi stati del vecchio workflow.\n" .
            "Lo stato 'modifica in corso' di un utente attivo rimane tale.\nLo stato 'modifica in corso' di un utente disattivo diventa lo stato 'disattivo'.\n" .
            "Lo stato 'validato' diventa 'attivo e validato'.\nLo stato 'da validare' diventa lo stato 'attivo'.\nI due vecchi stati 'attivo con richiesta " .
            "validazione' e 'attivo non validato' non sono gestiti e tutto viene spostato sullo stato 'attivo'. Vuoi continuare?"));
        if ($confirm) {
            return $this->proceed();
        } else {
            return $this->leave();
        }
    }
    
    private function proceed()
    {
        foreach ($this->oldToNewStatus as $oldStatus => $newStatus) {
            $whereCondition = ['status' => $newStatus];
            $setCondition = ['status' => $oldStatus];
            if ($oldStatus == 'UserProfileWorkflow/DISATTIVO') {
                $whereCondition['attivo'] = 0;
            }
            if (($oldStatus == 'UserProfileWorkflow/ATTIVOCONRICHIESTAVALIDAZIONE') || ($oldStatus == 'UserProfileWorkflow/ATTIVOCONRICHIESTAVALIDAZIONE')) {
                $setCondition['status'] = 'UserProfileWorkflow/ATTIVO';
            }
            
            MigrationCommon::printConsoleMessage(AmosAdmin::t('amosadmin', "Reverting UserProfile workflow status to " . $setCondition['status'] . " from $newStatus"));
            try {
                $this->update(UserProfile::tableName(), $setCondition, $whereCondition);
            } catch (\Exception $exception) {
                MigrationCommon::printConsoleMessage(AmosAdmin::t('amosadmin', "Error while reverting UserProfile workflow status to " . $setCondition['status'] . " from $newStatus"));
                return false;
            }
        }
        return true;
    }
    
    private function leave()
    {
        Console::error(AmosAdmin::t('amosadmin', "\nUscita forzata\n"));
        return false;
    }
    
    
    /**
     * @inheritdoc
     */
    protected function setWorkflow()
    {
        return ArrayHelper::merge(
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
                'label' => 'Editing in progress',
                'sort_order' => '1'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'TOVALIDATE',
                'workflow_id' => self::WORKFLOW_NAME,
                'label' => 'To validate',
                'sort_order' => '2'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_STATUS,
                'id' => 'VALIDATED',
                'workflow_id' => self::WORKFLOW_NAME,
                'label' => 'Validated',
                'sort_order' => '3'
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
            // Metadata for "Editing in progress" step
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'DRAFT',
                'key' => 'class',
                'value' => 'btn btn-navigation-primary'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'DRAFT',
                'key' => 'description',
                'value' => 'Editing in progress'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'DRAFT',
                'key' => 'label',
                'value' => 'Editing in progress'
            ],
            
            // Metadata for "To validate" step
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'TOVALIDATE',
                'key' => 'class',
                'value' => 'btn btn-navigation-primary'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'TOVALIDATE',
                'key' => 'description',
                'value' => 'It puts the user in validation request'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'TOVALIDATE',
                'key' => 'label',
                'value' => 'Validation request'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'TOVALIDATE',
                'key' => 'message',
                'value' => 'Do you want to set the validation request status?'
            ],
            
            // Metadata for "Validated" step
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'VALIDATED',
                'key' => 'class',
                'value' => 'btn btn-navigation-primary'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'VALIDATED',
                'key' => 'description',
                'value' => 'The user will be validated'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'VALIDATED',
                'key' => 'label',
                'value' => 'Validated'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => 'VALIDATED',
                'key' => 'message',
                'value' => 'Do you want to validate the user?'
            ]
        ];
    }
}
