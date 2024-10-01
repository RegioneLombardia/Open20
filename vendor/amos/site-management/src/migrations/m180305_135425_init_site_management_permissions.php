<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;

/**
 * Class m180305_135425_init_site_management_permissions
 */
class m180305_135425_init_site_management_permissions extends AmosMigrationPermissions
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
                'name' => 'SITE_MANAGEMENT_ADMINISTRATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Administrator role for the Site Management plugin',
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'SITE_MANAGEMENT_REDACTOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Redactor role for the Site Management plugin',
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],
        ];
    }

    private function setModelPermissions()
    {
        return [

            // Permissions for model PageContent
            [
                'name' => 'PAGECONTENT_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Create permission for model PageContent',
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ],
            [
                'name' => 'PAGECONTENT_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Read permission for model PageContent',
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR', 'SITE_MANAGEMENT_REDACTOR']
            ],
            [
                'name' => 'PAGECONTENT_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Update permission for model PageContent',
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR', 'SITE_MANAGEMENT_REDACTOR']
            ],
            [
                'name' => 'PAGECONTENT_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Delete permission for model PageContent',
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR']
            ]
        ];
    }

    private function setWidgetsPermissions()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';
        return [
            [
                'name' => \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementDashboard::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconSiteManagementDashboard',
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR', 'SITE_MANAGEMENT_REDACTOR']
            ],
            [
                'name' => \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementPageContent::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconSiteManagementPageContent',
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR', 'SITE_MANAGEMENT_REDACTOR']
            ]
        ];
    }
}
