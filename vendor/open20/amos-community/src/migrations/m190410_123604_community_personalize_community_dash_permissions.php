<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m190408_153604_community_amos_widgets_mm_permissions*/
class m190410_123604_community_personalize_community_dash_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'COMMUNITY_WIDGETS_ADMIN_PERSONALIZE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permission to manage communties widgets only for admin',
                ],
                [
                    'name' =>  'COMMUNITY_WIDGETS_CONFIGURATOR',
                    'update' => true,
                    'newValues' => [
                        'addParents' => ['COMMUNITY_MEMBER','AMMINISTRATORE_COMMUNITY']
                    ],
                ],
            ];
    }
}
