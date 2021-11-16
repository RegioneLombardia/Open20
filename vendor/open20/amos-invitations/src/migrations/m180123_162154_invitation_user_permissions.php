<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\invitations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
* Class m180123_162154_invitation_user_permissions*/
class m180123_162154_invitation_user_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
//                [
//                    'name' =>  'INVITATIONUSER_CREATE',
//                    'type' => Permission::TYPE_PERMISSION,
//                    'description' => 'Permesso di CREATE sul model InvitationUser',
//                    'ruleName' => null,
//                    'parent' => ['ADMIN']
//                ],
//                [
//                    'name' =>  'INVITATIONUSER_READ',
//                    'type' => Permission::TYPE_PERMISSION,
//                    'description' => 'Permesso di READ sul model InvitationUser',
//                    'ruleName' => null,
//                    'parent' => ['ADMIN']
//                    ],
//                [
//                    'name' =>  'INVITATIONUSER_UPDATE',
//                    'type' => Permission::TYPE_PERMISSION,
//                    'description' => 'Permesso di UPDATE sul model InvitationUser',
//                    'ruleName' => null,
//                    'parent' => ['ADMIN']
//                ],
//                [
//                    'name' =>  'INVITATIONUSER_DELETE',
//                    'type' => Permission::TYPE_PERMISSION,
//                    'description' => 'Permesso di DELETE sul model InvitationUser',
//                    'ruleName' => null,
//                    'parent' => ['ADMIN']
//                ],
            ];
    }
}
