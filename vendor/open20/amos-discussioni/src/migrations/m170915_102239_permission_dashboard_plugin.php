<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


class m170915_102239_permission_dashboard_plugin  extends AmosMigrationPermissions
{
    protected function setAuthorizations()
    {
        $this->authorizations = [

            [
                'name' => open20\amos\discussioni\widgets\icons\WidgetIconDiscussioniDashboard::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso widget della dashboard interna delle discussioni',
                'ruleName' => null,
                'parent' => ['AMMINISTRATORE_DISCUSSIONI', 'CREATORE_DISCUSSIONI', 'LETTORE_DISCUSSIONI', 'FACILITATORE_DISCUSSIONI'],
                'dontRemove' => true
            ],
        ];
    }
}
