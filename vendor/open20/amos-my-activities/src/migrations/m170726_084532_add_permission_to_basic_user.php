<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\myactivities\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m170712_141330_init_my_activities_permissions
 */
class m170726_084532_add_permission_to_basic_user extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';
        return [
            [
                'name' => \open20\amos\myactivities\widgets\icons\WidgetIconMyActivities::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconMyActivities',
                'parent' => ['BASIC_USER']
            ],
            [
                'name' => 'MYACTIVITIES_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Read permission for model MyActivities',
                'ruleName' => null,
                'parent' => ['BASIC_USER'],
                'dontRemove' => true
            ],
        ];
    }
}

