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
 * Class m171117_141402_add_documenti_permission_admin_all_widget
 */
class m171117_141402_add_documenti_permission_admin_all_widget extends AmosMigrationPermissions
{
    protected function setAuthorizations()
    {
        $this->authorizations = [
            [
                'name' => \open20\amos\documenti\widgets\icons\WidgetIconAdminAllDocumenti::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission description',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DOCUMENTI'],
                'dontRemove' => true
            ],

        ];
    }
}
