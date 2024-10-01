<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m230112_132647_permissions
 */
class m230202_132047_permissions_databanks extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [

            [
                'name' => 'MANAGE_ATTACH_GALLERY',
                'update' => true,
                'newValues' => [
                    'addParents' => ['CAPOREDATTORECMS', 'REDATTORECMS']
                ]
            ],
            [
                'name' => 'MANAGE_ATTACH_SHUTTERSTOCK',
                'update' => true,
                'newValues' => [
                    'addParents' => ['CAPOREDATTORECMS', 'REDATTORECMS']
                ]
            ] ,
            [
                'name' => 'ATTACH_DATABANK_FILE_ADMINISTRATOR',
                'update' => true,
                'newValues' => [
                    'addParents' => ['CAPOREDATTORECMS', 'REDATTORECMS']
                ]
            ]

        ];
    }

}
