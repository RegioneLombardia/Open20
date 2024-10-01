<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m180904_100403_site_management_landing_permissions*/
class m180904_100403_site_management_landing_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'SITEMANAGEMENTLANDING_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model SiteManagementLanding',
                    'ruleName' => null,
                    'parent' => ['SITE_MANAGEMENT_TEMPLATE_CREATOR']
                ],
                [
                    'name' =>  'SITEMANAGEMENTLANDING_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model SiteManagementLanding',
                    'ruleName' => null,
                    'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR','SITE_MANAGEMENT_TEMPLATE_CREATOR']
                    ],
                [
                    'name' =>  'SITEMANAGEMENTLANDING_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model SiteManagementLanding',
                    'ruleName' => null,
                    'parent' => ['SITE_MANAGEMENT_TEMPLATE_CREATOR']
                ],
                [
                    'name' =>  'SITEMANAGEMENTLANDING_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model SiteManagementLanding',
                    'ruleName' => null,
                    'parent' => ['SITE_MANAGEMENT_TEMPLATE_CREATOR']
                ],

            [
                'name' =>  'SITEMANAGEMENTLANDINGPUBBLICATION_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model SiteManagementLandingPubblication',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],
            [
                'name' =>  'SITEMANAGEMENTLANDINGPUBBLICATION_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model SiteManagementLandingPubblication',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],
            [
                'name' =>  'SITEMANAGEMENTLANDINGPUBBLICATION_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model SiteManagementLandingPubblication',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],
            [
                'name' =>  'SITEMANAGEMENTLANDINGPUBBLICATION_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model SiteManagementLandingPubblication',
                'ruleName' => null,
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],

            ];
    }
}
