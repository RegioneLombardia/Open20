<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m220117_120900_add_permission_widgets_expl
 */
class m220117_120900_add_permission_widgets_expl extends AmosMigrationPermissions
{

    protected function setRBACConfigurations()
    {

        return [
            [
                'name' => \open20\amos\documenti\widgets\graphics\WidgetGraphicsHierarchicalDocumentsBefeCommunity::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetGraphicsHierarchicalDocumentsBefeCommunity',
                'ruleName' => null,
                'parent' => ['ADMIN', 'BASIC_USER'],
            ],
        ];
    }
}