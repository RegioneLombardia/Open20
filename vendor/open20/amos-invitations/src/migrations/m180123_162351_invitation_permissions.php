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
 * Class m180123_162351_invitation_permissions*/
class m180123_162351_invitation_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'INVITATIONS_ADMINISTRATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruole adiminstrator',
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'INVITATIONS_BASIC_USER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruole adiminstrator',
                'parent' => ['VALIDATED_BASIC_USER']
            ],
            [
                'name' => 'INVITATION_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model Invitation',
                'parent' => ['INVITATIONS_ADMINISTRATOR', 'INVITATIONS_BASIC_USER']
            ],
            [
                'name' => 'INVITATION_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model Invitation',
                'parent' => ['INVITATIONS_ADMINISTRATOR']
            ],
            [
                'name' => 'INVITATION_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model Invitation',
                'parent' => ['INVITATIONS_ADMINISTRATOR']
            ],
            [
                'name' => \open20\amos\invitations\rules\ReadOwnInvitationRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model Invitation',
                'ruleName' => \open20\amos\invitations\rules\ReadOwnInvitationRule::className(),
                'parent' => ['INVITATIONS_BASIC_USER'],
                'children' => ['INVITATION_READ']
            ],
            [
                'name' => \open20\amos\invitations\rules\UpdateOwnInvitationRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model Invitation',
                'ruleName' => \open20\amos\invitations\rules\UpdateOwnInvitationRule::className(),
                'parent' => ['INVITATIONS_BASIC_USER'],
                'children' => ['INVITATION_UPDATE']
            ],
        ];
    }
}
