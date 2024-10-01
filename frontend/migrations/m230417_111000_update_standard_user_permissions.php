<?php

use open20\amos\admin\rbac\UpdateOwnUserProfile;
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m230417_111000_update_standard_user_permissions
 */
class m230417_111000_update_standard_user_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => substr(strrchr(\open20\amos\admin\rbac\UpdateOwnUserProfile::class, '\\'), 1),
                'type' => Permission::TYPE_PERMISSION,
                'update' => true,
                'newValues' => [
                    'removeParents' => ['STANDARD_USER'],
                ]
            ]
        ];
    }

}
