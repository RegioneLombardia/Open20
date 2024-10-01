<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m230612_120809_comment_dashboard_permissions*/
class m230612_120809_comment_dashboard_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            // Ruoli
            [
                'name' => 'COMMENTDASHBOARD_ADMINISTRATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Amministratore del model CommentDashboard',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'COMMENTDASHBOARD_READER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo con permesso di lettura del model CommentDashboard',
                'ruleName' => null,
                'parent' => []
            ],
            // Permessi
            [
                'name' => 'COMMENTDASHBOARD_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model CommentDashboard',
                'ruleName' => null,
                'parent' => ['COMMENTDASHBOARD_ADMINISTRATOR']
            ],
            [
                'name' => 'COMMENTDASHBOARD_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model CommentDashboard',
                'ruleName' => null,
                'parent' => ['COMMENTDASHBOARD_ADMINISTRATOR', 'COMMENTDASHBOARD_READER']
            ],
            [
                'name' => 'COMMENTDASHBOARD_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model CommentDashboard',
                'ruleName' => null,
                'parent' => ['COMMENTDASHBOARD_ADMINISTRATOR']
            ],
            [
                'name' => 'COMMENTDASHBOARD_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model CommentDashboard',
                'ruleName' => null,
                'parent' => ['COMMENTDASHBOARD_ADMINISTRATOR']
            ],

        ];
    }
}
