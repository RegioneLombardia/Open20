<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m200902_105500_registereduser_permissions*/
class m200902_105500_registereduser_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => \backend\modules\registeredusers\rules\CreateRegisteredUserRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'RULE for CREATE RegisteredUser',
                'ruleName' => \backend\modules\registeredusers\rules\CreateRegisteredUserRule::className(),
                'parent' => ['PC_STRUCTURE_ADMIN','PC_STRUCTURE_EDITOR']
            ],
            [
                'name' => \backend\modules\registeredusers\rules\DeleteRegisteredUserRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'RULE for CREATE RegisteredUser',
                'ruleName' => \backend\modules\registeredusers\rules\DeleteRegisteredUserRule::className(),
                'parent' => ['PC_STRUCTURE_ADMIN','PC_STRUCTURE_EDITOR']
            ],
            [
                'name' => \backend\modules\registeredusers\rules\ReadRegisteredUserRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'RULE for CREATE RegisteredUser',
                'ruleName' => \backend\modules\registeredusers\rules\ReadRegisteredUserRule::className(),
                'parent' => ['PC_STRUCTURE_ADMIN','PC_STRUCTURE_EDITOR']
            ],
            [
                'name' =>\backend\modules\registeredusers\rules\UpdateRegisteredUserRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'RULE for CREATE RegisteredUser',
                'ruleName' => \backend\modules\registeredusers\rules\UpdateRegisteredUserRule::className(),
                'parent' => ['PC_STRUCTURE_ADMIN','PC_STRUCTURE_EDITOR']
            ],
            [
                'name' => 'REGISTEREDUSER_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model REGISTEREDUSER',
                'ruleName' => null,
                'parent' => ['PC_ADMIN','PC_EDITOR',\backend\modules\registeredusers\rules\CreateRegisteredUserRule::className(),],
            ],
            [
                'name' => 'REGISTEREDUSER_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model REGISTEREDUSER',
                'ruleName' => null,
                'parent' => ['PC_ADMIN','PC_EDITOR',\backend\modules\registeredusers\rules\ReadRegisteredUserRule::className()],
            ],
            [
                'name' => 'REGISTEREDUSER_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model REGISTEREDUSER',
                'ruleName' => null,
                'parent' => ['PC_ADMIN','PC_EDITOR',\backend\modules\registeredusers\rules\UpdateRegisteredUserRule::className(),],
            ],
            [
                'name' => 'REGISTEREDUSER_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model REGISTEREDUSER',
                'ruleName' => null,
                'parent' => ['PC_ADMIN','PC_EDITOR',\backend\modules\registeredusers\rules\DeleteRegisteredUserRule::className(),],
            ],

        ];
    }
}
