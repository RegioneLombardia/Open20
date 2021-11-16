<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m170330_100316_add_alldiscussions_permission_all_plugin_roles
 */
class m170330_100316_add_alldiscussions_permission_all_plugin_roles extends AmosMigrationPermissions
{
    protected function setAuthorizations()
    {
        $this->authorizations = [
            [
                'name' => 'AMMINISTRATORE_DISCUSSIONI',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo di amministratore discussioni',
                'ruleName' => null,
                'parent' => ['ADMIN'],
                'dontRemove' => true
            ],
            [
                'name' => open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopicAll::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso widget della dashboard interna delle discussioni',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DISCUSSIONI', 'CREATORE_DISCUSSIONI', 'LETTORE_DISCUSSIONI', 'FACILITATORE_DISCUSSIONI'],
                'dontRemove' => true
            ],
            [
                'name' => open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniTopic::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso widget della dashboard interna delle discussioni',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DISCUSSIONI', 'CREATORE_DISCUSSIONI', 'LETTORE_DISCUSSIONI', 'FACILITATORE_DISCUSSIONI'],
                'dontRemove' => true
            ],
        ];
    }
}
