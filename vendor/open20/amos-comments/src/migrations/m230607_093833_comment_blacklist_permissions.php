<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m230607_093833_comment_blacklist_permissions
 */
class m230607_093833_comment_blacklist_permissions extends AmosMigrationPermissions
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
                'name' => 'COMMENTBLACKLIST_ADMINISTRATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Amministratore del model CommentBlacklist',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'COMMENTBLACKLIST_READER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo con permesso di lettura del model CommentBlacklist',
                'ruleName' => null,
                'parent' => []
            ],

            // Permessi
            [
                'name' => 'COMMENTBLACKLIST_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model CommentBlacklist',
                'ruleName' => null,
                'parent' => ['COMMENTBLACKLIST_ADMINISTRATOR']
            ],
            [
                'name' => 'COMMENTBLACKLIST_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model CommentBlacklist',
                'ruleName' => null,
                'parent' => ['COMMENTBLACKLIST_ADMINISTRATOR', 'COMMENTBLACKLIST_READER']
            ],
            [
                'name' => 'COMMENTBLACKLIST_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model CommentBlacklist',
                'ruleName' => null,
                'parent' => ['COMMENTBLACKLIST_ADMINISTRATOR']
            ],
            [
                'name' => 'COMMENTBLACKLIST_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model CommentBlacklist',
                'ruleName' => null,
                'parent' => ['COMMENTBLACKLIST_ADMINISTRATOR']
            ],

        ];
    }
}
