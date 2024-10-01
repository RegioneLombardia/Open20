<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m230201_102525_tag_free_permissions 
 */
class m230201_102525_tag_free_permissions extends AmosMigrationPermissions {

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations() {
        $prefixStr = '';

        return [
            [
                'name' => 'TAGFREE_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model TagFree',
                'ruleName' => null,
                'parent' => ['ADMIN', 'BASIC_USER']
            ],
            [
                'name' => 'TAGFREE_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model TagFree',
                'ruleName' => null,
                'parent' => ['ADMIN', 'BASIC_USER']
            ],
            [
                'name' => 'TAGFREE_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model TagFree',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'TAGFREE_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model TagFree',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
        ];
    }

}
