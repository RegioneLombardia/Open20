<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\organizzazioni\rules\AssociateProfiloUserToOrganizationsRule;
use yii\rbac\Permission;

/**
 * Class m191209_115333_modify_add_user_to_organization_permission
 */
class m191209_115333_modify_add_user_to_organization_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => AssociateProfiloUserToOrganizationsRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per ruolo responsabile di struttura di modificare le organizzazioni per cui è referente operativo',
                'ruleName' => AssociateProfiloUserToOrganizationsRule::className(),
                'parent' => [
                    'USERPROFILE_UPDATE'
                ],
                'children' => [
                    'ASSOCIATE_ORGANIZZAZIONI_TO_USER'
                ]
            ],
            [
                'name' => 'ASSOCIATE_ORGANIZZAZIONI_TO_USER',
                'update' => true,
                'newValues' => [
                    'addParents' => ['AMMINISTRATORE_ORGANIZZAZIONI'],
                    'removeParents' => ['USERPROFILE_UPDATE']
                ]
            ]
        ];
    }
}
