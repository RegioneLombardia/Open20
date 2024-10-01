<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


class m221104_100100_create_role_cms_page_editor extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            // CMS_PAGE_EDITOR ROLE
            [
                'name' => 'CMS_PAGE_EDITOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Role that can access to WidgetIconCmsDashboard',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            // Widget cms permission
            [
                'name' => \amos\cmsbridge\widgets\icons\WidgetIconCmsDashboard::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetIconCmsDashboard',
                'parent' => ['CMS_PAGE_EDITOR']
            ],
        ];
    }
}
