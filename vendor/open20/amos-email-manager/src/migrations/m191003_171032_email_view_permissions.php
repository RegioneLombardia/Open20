<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m191003_171032_email_view_permissions*/
class m191003_171032_email_view_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            /*[
                'name' => 'EMAILVIEW_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model EmailView',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],*/
            [
                'name' => 'EMAILVIEW_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model EmailView',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'EMAILVIEW_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model EmailView',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'EMAILVIEW_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model EmailView',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],

        ];
    }
}
