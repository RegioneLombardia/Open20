<?php

/**
 * Aria S.p.A.
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
class m211014_172351_invitation_permissions_delete extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'INVITATION_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model Invitation',
                'parent' => ['INVITATIONS_ADMINISTRATOR', \open20\amos\invitations\rules\UpdateOwnInvitationRule::className()]
            ],
        ];
    }
}
