<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m180802_113805_site_management_container_permissions*/
class m180802_113805_site_management_container_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'SITE_MANAGEMENT_TEMPLATE_CREATOR',
                    'type' => Permission::TYPE_ROLE,
                    'description' => 'Manage templates',
                    'ruleName' => null,
                    'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
                ],
            // -------------
                [
                    'name' =>  'SITEMANAGEMENTCONTAINER_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model SiteManagementContainer',
                    'ruleName' => null,
                    'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
                ],
                [
                    'name' =>  'SITEMANAGEMENTCONTAINER_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model SiteManagementContainer',
                    'ruleName' => null,
                    'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
                    ],
                [
                    'name' =>  'SITEMANAGEMENTCONTAINER_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model SiteManagementContainer',
                    'ruleName' => null,
                    'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
                ],
                [
                    'name' =>  'SITEMANAGEMENTCONTAINER_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model SiteManagementContainer',
                    'ruleName' => null,
                    'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
                ],
            //----------------------

            [
                'name' =>  'SITEMANAGEMENTTEMPLATE_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model SiteManagementTemplate',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_TEMPLATE_CREATOR']
            ],
            [
                'name' =>  'SITEMANAGEMENTTEMPLATE_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model SiteManagementTemplate',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_TEMPLATE_CREATOR']
            ],
            [
                'name' =>  'SITEMANAGEMENTTEMPLATE_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model SiteManagementTemplate',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_TEMPLATE_CREATOR']
            ],
            [
                'name' =>  'SITEMANAGEMENTTEMPLATE_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model SiteManagementTemplate',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_TEMPLATE_CREATOR']
            ],
            // ----------------
            [
                'name' =>  'SITEMANAGEMENTELEMENT_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model SiteManagementElement',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],
            [
                'name' =>  'SITEMANAGEMENTELEMENT_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model SiteManagementElement',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],
            [
                'name' =>  'SITEMANAGEMENTELEMENT_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model SiteManagementElement',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],
            [
                'name' =>  'SITEMANAGEMENTELEMENT_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model SiteManagementElement',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],

            // -------------------

            [
                'name' =>  'SITEMANAGEMENTCONTAINERELEMENTMM_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model SiteManagementContainerElementMm',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],
            [
                'name' =>  'SITEMANAGEMENTCONTAINERELEMENTMM_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model SiteManagementContainerElementMm',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],
            [
                'name' =>  'SITEMANAGEMENTCONTAINERELEMENTMM_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model SiteManagementContainerElementMm',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],
            [
                'name' =>  'SITEMANAGEMENTCONTAINERELEMENTMM_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model SiteManagementContainerElementMm',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],
            //----------------

            [
                'name' =>  'SITEMANAGECONTELEMPUBBLICATION_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model SiteManageContElemPubblication',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],
            [
                'name' =>  'SITEMANAGECONTELEMPUBBLICATION_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model SiteManageContElemPubblication',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],
            [
                'name' =>  'SITEMANAGECONTELEMPUBBLICATION_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model SiteManageContElemPubblication',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],
            [
                'name' =>  'SITEMANAGECONTELEMPUBBLICATION_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model SiteManageContElemPubblication',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],
            // ----------------

            [
                'name' =>  'SITEMANAGEPUBBLICATIONTYPE_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model SiteManagePubblicationType',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],
            [
                'name' =>  'SITEMANAGEPUBBLICATIONTYPE_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model SiteManagePubblicationType',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],
            [
                'name' =>  'SITEMANAGEPUBBLICATIONTYPE_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model SiteManagePubblicationType',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],
            [
                'name' =>  'SITEMANAGEPUBBLICATIONTYPE_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model SiteManagePubblicationType',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],

            // ---------------
            [
                'name' =>  'SITEMANAGECONTELEMPUBBLICATIONCLASS_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model SiteManageContElemPubblicationClass',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],
            [
                'name' =>  'SITEMANAGECONTELEMPUBBLICATIONCLASS_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model SiteManageContElemPubblicationClass',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],
            [
                'name' =>  'SITEMANAGECONTELEMPUBBLICATIONCLASS_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model SiteManageContElemPubblicationClass',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],
            [
                'name' =>  'SITEMANAGECONTELEMPUBBLICATIONCLASS_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model SiteManageContElemPubblicationClass',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],
            
            // -------------

            [
                'name' =>  'SITEMANAGECONTELEMPUBBLICATIONUSERMM_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model SiteManageContElemPubblicationUserMm',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],
            [
                'name' =>  'SITEMANAGECONTELEMPUBBLICATIONUSERMM_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model SiteManageContElemPubblicationUserMm',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],
            [
                'name' =>  'SITEMANAGECONTELEMPUBBLICATIONUSERMM_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model SiteManageContElemPubblicationUserMm',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],
            [
                'name' =>  'SITEMANAGECONTELEMPUBBLICATIONUSERMM_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model SiteManageContElemPubblicationUserMm',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],



        ];
    }
}
