<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m170330_092636_add_alldocumemts_permission_all_plugin_roles
 */
class m170330_092636_add_alldocumemts_permission_all_plugin_roles extends AmosMigrationPermissions
{
    protected function setAuthorizations()
    {
        $this->authorizations = [
            [
                'name' => 'AMMINISTRATORE_DOCUMENTI',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Amministratore documenti',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => \open20\amos\documenti\widgets\icons\WidgetIconAllDocumenti::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission description',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DOCUMENTI', 'CREATORE_DOCUMENTI', 'LETTORE_DOCUMENTI', 'FACILITATORE_DOCUMENTI']
            ],
            [
                'name' => \open20\amos\documenti\widgets\icons\WidgetIconDocumenti::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission description',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DOCUMENTI', 'CREATORE_DOCUMENTI', 'LETTORE_DOCUMENTI', 'FACILITATORE_DOCUMENTI']
            ],
        ];
    }
}
