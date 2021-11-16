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
use open20\amos\community\rules\UpdateOwnWorkgroupsRule;
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;

/**
 * Class m170511_162722_add_community_workgroup_update_permissions
 */
class m170511_162722_add_community_workgroup_update_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return ArrayHelper::merge(
            $this->setModelPermissions(),
            $this->setWorkflowPermissions()
        );
    }
    
    private function setModelPermissions()
    {
        return [
            [
                'name' => UpdateOwnWorkgroupsRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Update permission for model Community',
                'ruleName' => UpdateOwnWorkgroupsRule::className(),
            ],
            [
                'name' => 'COMMUNITY_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Update permission for model Community',
                'parent' => [UpdateOwnWorkgroupsRule::className()],
                'dontRemove' => true
            ]
        ];
    }
    
    private function setWorkflowPermissions()
    {
        return [
            [
                'name' => Community::COMMUNITY_WORKFLOW_STATUS_DRAFT,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission workflow community status draft',
                'parent' => [UpdateOwnWorkgroupsRule::className()],
                'dontRemove' => true
            ],
            [
                'name' => Community::COMMUNITY_WORKFLOW_STATUS_TO_VALIDATE,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission workflow community status to validate',
                'parent' => [UpdateOwnWorkgroupsRule::className()],
                'dontRemove' => true
            ],
            [
                'name' => Community::COMMUNITY_WORKFLOW_STATUS_VALIDATED,
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission workflow community status validated',
                'parent' => [UpdateOwnWorkgroupsRule::className()],
                'dontRemove' => true
            ]
        ];
    }
}
