<?php

use open20\amos\admin\rbac\UpdateOwnUserProfile;
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m230331_105700_create_role_standard_user
 */
class m230331_105700_create_role_standard_user extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'STANDARD_USER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo di utente iscritto alla newsletter'
            ],
            [
                'name' => substr(strrchr(\open20\amos\admin\rbac\UpdateOwnUserProfile::class, '\\'), 1),
                'type' => Permission::TYPE_PERMISSION,
                'update' => true,
                'newValues' => [
                    'addParents' => ['STANDARD_USER'],
                ]
            ]
        ];
    }

}
