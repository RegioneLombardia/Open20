<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m171120_081544_add_community_permission_admin_all_widget
 */
class m171120_081544_add_community_permission_admin_all_widget extends AmosMigrationPermissions
{
    protected function setAuthorizations()
    {
        $this->authorizations = [
            [
                'name' => \open20\amos\community\widgets\icons\WidgetIconAdminAllCommunity::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission description',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_COMMUNITY' ],
                'dontRemove' => true
            ],

        ];
    }
}
