<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m180731_152624_site_management_section_permissions*/
class m180903_152724_site_management_fix_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'SITEMANAGEMENTCONTAINERELEMFIELDSVAL_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_ADMINISTRATOR'],
                ]
            ],
            [
                'name' => 'SITEMANAGEMENTCONTAINERELEMFIELDSVAL_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_ADMINISTRATOR'],
                ]
            ],
            [
                'name' => 'SITEMANAGEMENTCONTAINERELEMFIELDSVAL_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_ADMINISTRATOR'],
                ]
            ],
            [
                'name' => 'SITEMANAGEMENTCONTAINERELEMFIELDSVAL_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_ADMINISTRATOR'],
                ]
            ],
 // read PERMISSIONS
            [
                'name' => 'SITEMANAGEMENTTEMPLATE_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_ADMINISTRATOR'],
                ]
            ],
            [
                'name' => 'SITEMANAGEMENTFIELDSTYPE_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_ADMINISTRATOR'],
                ]
            ],
            [
                'name' => 'SITEMANAGEMENTTEMPLATEFIELDS_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['SITE_MANAGEMENT_ADMINISTRATOR'],
                ]
            ],






        ];
    }
}
