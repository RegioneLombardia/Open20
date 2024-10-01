<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m230201_103237_tag_free_model_mm_permissions 
 */
class m230201_103237_tag_free_model_mm_permissions extends AmosMigrationPermissions {

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations() {
        $prefixStr = '';

        return [
            [
                'name' => 'TAGFREEMODELMM_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model TagFreeModelMm',
                'ruleName' => null,
                'parent' => ['ADMIN', 'BASIC_USER']
            ],
            [
                'name' => 'TAGFREEMODELMM_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model TagFreeModelMm',
                'ruleName' => null,
                'parent' => ['ADMIN', 'BASIC_USER']
            ],
            [
                'name' => 'TAGFREEMODELMM_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model TagFreeModelMm',
                'ruleName' => null,
                'parent' => ['ADMIN', 'BASIC_USER']
            ],
            [
                'name' => 'TAGFREEMODELMM_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model TagFreeModelMm',
                'ruleName' => null,
                'parent' => ['ADMIN', 'BASIC_USER']
            ],
        ];
    }

}
