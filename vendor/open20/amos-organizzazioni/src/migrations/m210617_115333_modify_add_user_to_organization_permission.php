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
 * Class m210617_115333_modify_add_user_to_organization_permission
 */
class m210617_115333_modify_add_user_to_organization_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'ASSOCIATE_ORGANIZZAZIONI_TO_USER',
                'update' => true,
                'newValues' => [
                    'addParents' => ['USERPROFILE_UPDATE']
                ]
            ]
        ];
    }
}
