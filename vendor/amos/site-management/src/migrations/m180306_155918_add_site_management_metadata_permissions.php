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
 * Class m180306_155918_add_site_management_metadata_permissions
 */
class m180306_155918_add_site_management_metadata_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return ArrayHelper::merge(
            $this->setModelPermissions(),
            $this->setWidgetsPermissions()
        );
    }

    private function setModelPermissions()
    {
        return [

            // Permissions for model Metadata
            [
                'name' => 'METADATA_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Create permission for model Metadata',
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR', 'SITE_MANAGEMENT_REDACTOR']
            ],
            [
                'name' => 'METADATA_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Read permission for model Metadata',
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR', 'SITE_MANAGEMENT_REDACTOR']
            ],
            [
                'name' => 'METADATA_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Update permission for model Metadata',
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR', 'SITE_MANAGEMENT_REDACTOR']
            ],
            [
                'name' => 'METADATA_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Delete permission for model Metadata',
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR', 'SITE_MANAGEMENT_REDACTOR']
            ]
        ];
    }

    private function setWidgetsPermissions()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';
        return [
            [
                'name' => \amos\sitemanagement\widgets\icons\WidgetIconSiteManagementMetadata::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconSiteManagementMetadata',
                'parent' => ['SITE_MANAGEMENT_ADMINISTRATOR', 'SITE_MANAGEMENT_REDACTOR']
            ]
        ];
    }
}
