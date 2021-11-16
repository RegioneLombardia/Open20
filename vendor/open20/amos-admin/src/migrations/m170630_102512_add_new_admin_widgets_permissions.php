<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m170630_102512_add_new_admin_widgets_permissions
 */
class m170630_102512_add_new_admin_widgets_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';
        return [
            [
                'name' => \open20\amos\admin\widgets\graphics\WidgetGraphicMyProfile::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['BASIC_USER']
                ]
            ],
            [
                'name' => \open20\amos\admin\widgets\icons\WidgetIconMyProfile::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['BASIC_USER']
                ]
            ],
            [
                'name' => \open20\amos\admin\widgets\icons\WidgetIconAdmin::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconAdmin',
                'parent' => ['BASIC_USER', 'ADMIN']
            ],
            [
                'name' => \open20\amos\admin\widgets\icons\WidgetIconUserProfile::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['BASIC_USER']
                ]
            ],
            [
                'name' => \open20\amos\admin\widgets\icons\WidgetIconValidatedUserProfiles::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconValidatedUserProfiles',
                'parent' => ['BASIC_USER']
            ],
            [
                'name' => \open20\amos\admin\widgets\icons\WidgetIconFacilitatorUserProfiles::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconFacilitatorUserProfiles',
                'parent' => ['BASIC_USER']
            ],
            [
                'name' => \open20\amos\admin\widgets\icons\WidgetIconCommunityManagerUserProfiles::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconCommunityManagerUserProfiles',
                'parent' => ['BASIC_USER']
            ],
            [
                'name' => \open20\amos\admin\widgets\icons\WidgetIconInactiveUserProfiles::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconInactiveUserProfiles',
                'parent' => ['ADMIN']
            ]
        ];
    }
}
