<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\events\migrations
 * @category   CategoryName
 */

use open20\amos\community\models\Community;
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\db\Query;

/**
 * Class m170421_083428_add_events_creator_permissions_community_workflow_status
 */
class m170421_083428_add_events_creator_permissions_community_workflow_status extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $authorizations = [];
        
        $communityWorkflowStates = [
            Community::COMMUNITY_WORKFLOW_STATUS_DRAFT,
            Community::COMMUNITY_WORKFLOW_STATUS_TO_VALIDATE,
            Community::COMMUNITY_WORKFLOW_STATUS_VALIDATED,
            Community::COMMUNITY_WORKFLOW_STATUS_NOT_VALIDATED
        ];
        
        foreach ($communityWorkflowStates as $communityWorkflowStatus) {
            $query = new Query();
            $query->from('auth_item')->andWhere(['name' => $communityWorkflowStatus]);
            $communityWorkflowStatusFound = $query->one();
            if ($communityWorkflowStatusFound !== false) {
                $authorizations[] = [
                    'name' => $communityWorkflowStatus,
                    'update' => true,
                    'newValues' => [
                        'addParents' => ['EVENTS_ADMINISTRATOR', 'EVENTS_CREATOR', 'EVENTS_VALIDATOR', 'PLATFORM_EVENTS_VALIDATOR']
                    ]
                ];
            }
        }
        
        return $authorizations;
    }
}
