<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m180802_150827_site_management_fields_type_permissions*/
class m180802_150827_site_management_fields_type_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'SITEMANAGEMENTFIELDSTYPE_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model SiteManagementFieldsType',
                    'ruleName' => null,
                    'parent' => ['SITE_MANAGEMENT_TEMPLATE_CREATOR']
                ],
                [
                    'name' =>  'SITEMANAGEMENTFIELDSTYPE_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model SiteManagementFieldsType',
                    'ruleName' => null,
                    'parent' => ['SITE_MANAGEMENT_TEMPLATE_CREATOR']
                    ],
                [
                    'name' =>  'SITEMANAGEMENTFIELDSTYPE_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model SiteManagementFieldsType',
                    'ruleName' => null,
                    'parent' => ['SITE_MANAGEMENT_TEMPLATE_CREATOR']
                ],
                [
                    'name' =>  'SITEMANAGEMENTFIELDSTYPE_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model SiteManagementFieldsType',
                    'ruleName' => null,
                    'parent' => ['SITE_MANAGEMENT_TEMPLATE_CREATOR']
                ],
            
            // ---------------

            [
                'name' =>  'SITEMANAGEMENTCONTAINERELEMFIELDSVAL_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model SiteManagementContainerElemFieldsVal',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_TEMPLATE_CREATOR']
            ],
            [
                'name' =>  'SITEMANAGEMENTCONTAINERELEMFIELDSVAL_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model SiteManagementContainerElemFieldsVal',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_TEMPLATE_CREATOR']
            ],
            [
                'name' =>  'SITEMANAGEMENTCONTAINERELEMFIELDSVAL_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model SiteManagementContainerElemFieldsVal',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_TEMPLATE_CREATOR']
            ],
            [
                'name' =>  'SITEMANAGEMENTCONTAINERELEMFIELDSVAL_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model SiteManagementContainerElemFieldsVal',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_TEMPLATE_CREATOR']
            ],
            // ---------------------------

            [
                'name' =>  'SITEMANAGEMENTTEMPLATEFIELDS_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model SiteManagementTemplateFields',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_TEMPLATE_CREATOR']
            ],
            [
                'name' =>  'SITEMANAGEMENTTEMPLATEFIELDS_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model SiteManagementTemplateFields',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_TEMPLATE_CREATOR']
            ],
            [
                'name' =>  'SITEMANAGEMENTTEMPLATEFIELDS_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model SiteManagementTemplateFields',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_TEMPLATE_CREATOR']
            ],
            [
                'name' =>  'SITEMANAGEMENTTEMPLATEFIELDS_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model SiteManagementTemplateFields',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_TEMPLATE_CREATOR']
            ],

        ];
    }
}
