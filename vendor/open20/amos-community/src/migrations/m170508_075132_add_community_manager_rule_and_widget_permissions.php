<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\community\models\Community;
use open20\amos\community\rules\UpdateCommunitiesManagerRule;
use open20\amos\community\rules\ValidateSubcommunitiesRule;
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;

/**
 * Class m170508_075132_add_community_manager_rule_and_widget_permissions
 */
class m170508_075132_add_community_manager_rule_and_widget_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return ArrayHelper::merge(
            $this->setModelPermissions(),
            $this->setWorkflowPermissions(),
            $this->setWidgetPermissions()
        );
    }

    private function setModelPermissions()
    {
        return [
            [
                'name' => UpdateCommunitiesManagerRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Update permission for model Community',
                'ruleName' => UpdateCommunitiesManagerRule::className(),
                'parent' => ['COMMUNITY_MEMBER'],
            ],
            [
                'name' => ValidateSubcommunitiesRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Update permission for model Community',
                'ruleName' => ValidateSubcommunitiesRule::className(),
                'parent' => ['COMMUNITY_CREATOR', 'COMMUNITY_VALIDATE'],
            ],
            [
                'name' => 'COMMUNITY_VALIDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Validate permission for model Community',
                'ruleName' => null,
                'parent' => ['COMMUNITY_VALIDATOR'],
                'dontRemove' => true
            ],
            [
                'name' => 'COMMUNITY_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Update permission for model Community',
                'ruleName' => null,
                'parent' => [UpdateCommunitiesManagerRule::className(), ValidateSubcommunitiesRule::className()],
                'dontRemove' => true
            ],
        ];
    }

    private function setWorkflowPermissions()
    {
        return [
            [
                'name' => Community::COMMUNITY_WORKFLOW_STATUS_DRAFT,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permession workflow community status draft',
                'ruleName' => null,
                'parent' => ['COMMUNITY_MEMBER', ValidateSubcommunitiesRule::className()],
                'dontRemove' => true
            ],
            [
                'name' => Community::COMMUNITY_WORKFLOW_STATUS_TO_VALIDATE,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permession workflow community status to validate',
                'ruleName' => null,
                'parent' => ['COMMUNITY_MEMBER', ValidateSubcommunitiesRule::className()],
                'dontRemove' => true
            ],
            [
                'name' => Community::COMMUNITY_WORKFLOW_STATUS_VALIDATED,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permession workflow community status validated',
                'ruleName' => null,
                'parent' => [ValidateSubcommunitiesRule::className()],
                'dontRemove' => true
            ],
        ];
    }

    private function setWidgetPermissions()
    {
        return [
            [
                'name' => 'open20\amos\community\widgets\icons\WidgetIconToValidateCommunities',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Dashboard permission for widget ' . 'WidgetIconToValidateCommunities',
                'ruleName' => null,
                'parent' => ['COMMUNITY_VALIDATOR', ValidateSubcommunitiesRule::className()],
                'dontRemove' => true
            ],
            [
                'name' => \open20\amos\community\widgets\icons\WidgetIconCommunity::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Dashboard permission for widget ' . 'WidgetIconCommunity',
                'ruleName' => null,
                'parent' => ['COMMUNITY_VALIDATOR', 'COMMUNITY_CREATOR', 'COMMUNITY_READER'],
                'dontRemove' => true
            ],
            [
                'name' => \open20\amos\community\widgets\icons\WidgetIconCommunityDashboard::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Dashboard permission for widget ' . 'WidgetIconCommunityDashboard',
                'ruleName' => null,
                'parent' => ['COMMUNITY_VALIDATOR', 'COMMUNITY_CREATOR', 'COMMUNITY_READER'],
                'dontRemove' => true
            ],
            [
                'name' => \open20\amos\community\widgets\icons\WidgetIconCreatedByCommunities::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Dashboard permission for widget ' . 'WidgetIconCreatedByCommunity',
                'ruleName' => null,
                'parent' => ['COMMUNITY_CREATOR'],
                'dontRemove' => true
            ],
            [
                'name' => \open20\amos\community\widgets\icons\WidgetIconMyCommunities::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Dashboard permission for widget ' . 'WidgetIconMyCommunities',
                'ruleName' => null,
                'parent' => ['COMMUNITY_CREATOR', 'COMMUNITY_READER'],
                'dontRemove' => true
            ],
        ];
    }
}
