<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    cruscotto-lavoro\platform\common\console\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m190314_115601_add_new_admin_widgets_users_permission
 */
class m190314_115601_add_new_admin_widgets_users_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard widget ';
        return [
            [
                'name' => \open20\amos\admin\widgets\graphics\WidgetGraphicsUsers::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetGraphicsUsers',
                'parent' => ['BASIC_USER']
            ]
        ];
    }
}