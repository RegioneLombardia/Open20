<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m190408_153604_community_amos_widgets_mm_permissions*/
class m190408_153604_community_amos_widgets_mm_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'COMMUNITY_WIDGETS_CONFIGURATOR',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permission to manage communties widgets',
                ],
                [
                    'name' =>  'COMMUNITYAMOSWIDGETSMM_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model CommunityAmosWidgetsMm',
                    'ruleName' => null,
                    'parent' => ['COMMUNITY_WIDGETS_CONFIGURATOR']
                ],
                [
                    'name' =>  'COMMUNITYAMOSWIDGETSMM_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model CommunityAmosWidgetsMm',
                    'ruleName' => null,
                    'parent' => ['COMMUNITY_WIDGETS_CONFIGURATOR']
                    ],
                [
                    'name' =>  'COMMUNITYAMOSWIDGETSMM_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model CommunityAmosWidgetsMm',
                    'ruleName' => null,
                    'parent' => ['COMMUNITY_WIDGETS_CONFIGURATOR']
                ],
                [
                    'name' =>  'COMMUNITYAMOSWIDGETSMM_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model CommunityAmosWidgetsMm',
                    'ruleName' => null,
                    'parent' => ['COMMUNITY_WIDGETS_CONFIGURATOR']
                ],

            ];
    }
}
