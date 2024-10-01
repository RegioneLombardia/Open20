<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\admin\models\UserProfile;
use open20\amos\core\migration\AmosMigrationWorkflow;

/**
 * Class m210506_163759_add_user_profile_workflow_metadata_tooltip_label
 */
class m210506_163759_add_user_profile_workflow_metadata_tooltip_label extends AmosMigrationWorkflow
{
    /**
     * @inheritdoc
     */
    protected function setWorkflow()
    {
        return [
            
            // Draft
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => UserProfile::USERPROFILE_WORKFLOW,
                'status_id' => 'DRAFT',
                'key' => 'tooltipLabel',
                'value' => '#DRAFT_tooltipLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => UserProfile::USERPROFILE_WORKFLOW,
                'status_id' => 'DRAFT',
                'key' => 'tooltipIcon',
                'value' => 'icon-status-draft'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => UserProfile::USERPROFILE_WORKFLOW,
                'status_id' => 'DRAFT',
                'key' => 'tooltipMdi',
                'value' => 'mdi-autorenew'
            ],
    
            // To validate
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => UserProfile::USERPROFILE_WORKFLOW,
                'status_id' => 'TOVALIDATE',
                'key' => 'tooltipLabel',
                'value' => '#TOVALIDATE_tooltipLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => UserProfile::USERPROFILE_WORKFLOW,
                'status_id' => 'TOVALIDATE',
                'key' => 'tooltipIcon',
                'value' => 'icon-status-tovalidated'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => UserProfile::USERPROFILE_WORKFLOW,
                'status_id' => 'TOVALIDATE',
                'key' => 'tooltipMdi',
                'value' => 'mdi-exclamation-thick'
            ],
    
            // Validated
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => UserProfile::USERPROFILE_WORKFLOW,
                'status_id' => 'VALIDATED',
                'key' => 'tooltipLabel',
                'value' => '#VALIDATED_tooltipLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => UserProfile::USERPROFILE_WORKFLOW,
                'status_id' => 'VALIDATED',
                'key' => 'tooltipIcon',
                'value' => 'icon-status-validated'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => UserProfile::USERPROFILE_WORKFLOW,
                'status_id' => 'VALIDATED',
                'key' => 'tooltipMdi',
                'value' => 'mdi-check'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => UserProfile::USERPROFILE_WORKFLOW,
                'status_id' => 'VALIDATED',
                'key' => 'tooltipSkip',
                'value' => '1'
            ],
    
            // Not validated
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => UserProfile::USERPROFILE_WORKFLOW,
                'status_id' => 'NOTVALIDATED',
                'key' => 'tooltipLabel',
                'value' => '#NOTVALIDATED_tooltipLabel'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => UserProfile::USERPROFILE_WORKFLOW,
                'status_id' => 'NOTVALIDATED',
                'key' => 'tooltipIcon',
                'value' => 'icon-status-notvalidated'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => UserProfile::USERPROFILE_WORKFLOW,
                'status_id' => 'NOTVALIDATED',
                'key' => 'tooltipMdi',
                'value' => 'mdi-minus'
            ]
        ];
    }
}
