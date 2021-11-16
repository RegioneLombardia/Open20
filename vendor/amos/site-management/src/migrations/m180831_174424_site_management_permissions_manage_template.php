<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m180731_152624_site_management_section_permissions*/
class m180831_174424_site_management_permissions_manage_template extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                      [
            'name' => 'SITE_MANAGEMENT_TEMPLATE_CREATOR',
            'update' => true,
            'newValues' => [
                'removeParents' => ['SITE_MANAGEMENT_ADMINISTRATOR'],
            ]
        ],

        ];
    }
}
