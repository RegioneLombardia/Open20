<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m170330_080532_add_allnews_permission_all_plugin_roles
 */
class m170330_080532_add_allnews_permission_all_plugin_roles extends AmosMigrationPermissions
{
    protected function setAuthorizations()
    {
        $this->authorizations = [
            [
                'name' => \open20\amos\news\widgets\icons\WidgetIconAllNews::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission description',
                'ruleName' => null,
                'parent' => ['LETTORE_NEWS', 'CREATORE_NEWS', 'FACILITATORE_NEWS', 'AMMINISTRATORE_NEWS' ],
                'dontRemove' => true
            ],
            [
                'name' => \open20\amos\news\widgets\icons\WidgetIconNews::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission description',
                'ruleName' => null,
                'parent' => ['LETTORE_NEWS', 'CREATORE_NEWS', 'FACILITATORE_NEWS', 'AMMINISTRATORE_NEWS' ],
                'dontRemove' => true
            ]
        ];
    }
}
