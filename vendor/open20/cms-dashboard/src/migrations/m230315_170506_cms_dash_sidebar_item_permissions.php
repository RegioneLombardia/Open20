<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m230315_171148_cms_dash_sidebar_item_permissions*/
class m230315_170506_cms_dash_sidebar_item_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' =>  'CMSDASHSIDEBARITEM_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model CmsDashSidebarItem',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' =>  'CMSDASHSIDEBARITEM_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model CmsDashSidebarItem',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' =>  'CMSDASHSIDEBARITEM_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model CmsDashSidebarItem',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' =>  'CMSDASHSIDEBARITEM_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model CmsDashSidebarItem',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],

        ];
    }
}