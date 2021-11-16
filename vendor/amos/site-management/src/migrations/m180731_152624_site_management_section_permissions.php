<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m180731_152624_site_management_section_permissions*/
class m180731_152624_site_management_section_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'SITEMANAGEMENTSECTION_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model SiteManagementSection',
                    'ruleName' => null,
                    'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
                ],
                [
                    'name' =>  'SITEMANAGEMENTSECTION_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model SiteManagementSection',
                    'ruleName' => null,
                    'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
                    ],
                [
                    'name' =>  'SITEMANAGEMENTSECTION_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model SiteManagementSection',
                    'ruleName' => null,
                    'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
                ],
                [
                    'name' =>  'SITEMANAGEMENTSECTION_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model SiteManagementSection',
                    'ruleName' => null,
                    'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
                ],
            // ---------------------

            [
                'name' =>  'SITEMANAGEMENTSLIDER_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model SiteManagementSlider',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],
            [
                'name' =>  'SITEMANAGEMENTSLIDER_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model SiteManagementSlider',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],
            [
                'name' =>  'SITEMANAGEMENTSLIDER_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model SiteManagementSlider',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],
            [
                'name' =>  'SITEMANAGEMENTSLIDER_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model SiteManagementSlider',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],

            // ------------------
            [
                'name' =>  'SITEMANAGEMENTSLIDERELEM_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model SiteManagementSliderElem',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],
            [
                'name' =>  'SITEMANAGEMENTSLIDERELEM_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model SiteManagementSliderElem',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],
            [
                'name' =>  'SITEMANAGEMENTSLIDERELEM_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model SiteManagementSliderElem',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],
            [
                'name' =>  'SITEMANAGEMENTSLIDERELEM_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model SiteManagementSliderElem',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],



        ];
    }
}
