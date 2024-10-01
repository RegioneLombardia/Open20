<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\organizzazioni\rules\ProfiloGroupsRule;
use open20\amos\organizzazioni\widgets\icons\WidgetIconProfiloGroups;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;

/**
 * Class m210521_103550_add_profilo_groups_permissions
 */
class m210521_103550_add_profilo_groups_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return ArrayHelper::merge(
            $this->setPluginRoles(),
            $this->setModelPermissions(),
            $this->setWidgetsPermissions()
        );
    }
    
    private function setPluginRoles()
    {
        return [
            [
                'name' => 'PROFILO_GROUPS_MANAGER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Role to manage EventRoom',
                'ruleName' => ProfiloGroupsRule::className(),
                'parent' => ['AMMINISTRATORE_ORGANIZZAZIONI']
            ]
        ];
    }
    
    private function setModelPermissions()
    {
        return [
            [
                'name' => 'PROFILOGROUPS_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Create permission for model ProfiloGroups',
                'parent' => ['PROFILO_GROUPS_MANAGER']
            ],
            [
                'name' => 'PROFILOGROUPS_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Read permission for model ProfiloGroups',
                'parent' => ['PROFILO_GROUPS_MANAGER']
            ],
            [
                'name' => 'PROFILOGROUPS_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Update permission for model ProfiloGroups',
                'parent' => ['PROFILO_GROUPS_MANAGER']
            ],
            [
                'name' => 'PROFILOGROUPS_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Delete permission for model ProfiloGroups',
                'parent' => ['PROFILO_GROUPS_MANAGER']
            ]
        ];
    }
    
    private function setWidgetsPermissions()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';
        return [
            [
                'name' => WidgetIconProfiloGroups::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconProfiloGroups',
                'parent' => ['PROFILO_GROUPS_MANAGER']
            ]
        ];
    }
}
