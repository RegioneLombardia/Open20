<?php

use \open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;
use open20\amos\projectmanagement\rules\TaskOrganizationsMmRule;

class m180827_150544_to_validate_community_workflow_rule extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {

        return [

            [
                'name' => \open20\amos\community\rules\workflow\CommunityWorkflowDraftRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Rule to validate rule',
                'ruleName' => \open20\amos\community\rules\workflow\CommunityWorkflowDraftRule::className(),
                'parent' => ['AMMINISTRATORE_COMMUNITY', 'COMMUNITY_CREATE', 'COMMUNITY_CREATOR', 'COMMUNITY_MEMBER', 'COMMUNITY_UPDATE', 'COMMUNITY_VALIDATE', 'open20\amos\community\rules\UpdateOwnWorkgroupsRulE'],
                ],
            [
                'name' => 'CommunityWorkflow/DRAFT',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['AMMINISTRATORE_COMMUNITY', 'COMMUNITY_CREATE', 'COMMUNITY_CREATOR', 'COMMUNITY_MEMBER', 'COMMUNITY_UPDATE', 'COMMUNITY_VALIDATE', 'open20\amos\community\rules\UpdateOwnWorkgroupsRulE'],
                    'addParents' => [\open20\amos\community\rules\workflow\CommunityWorkflowDraftRule::className()]
                ],
            ],


        ];
    }
}
