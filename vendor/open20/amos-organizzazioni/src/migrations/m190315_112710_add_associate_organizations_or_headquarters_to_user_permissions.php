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
use yii\rbac\Permission;

/**
 * Class m190315_112710_add_associate_organizations_or_headquarters_to_user_permissions
 */
class m190315_112710_add_associate_organizations_or_headquarters_to_user_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'ASSOCIATE_PROFILO_TO_USER_PERMISSION',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per poter associarsi a una organizzazione nel profilo utente',
                'parent' => ['AMMINISTRATORE_ORGANIZZAZIONI', 'LETTORE_ORGANIZZAZIONI']
            ],
            [
                'name' => 'ASSOCIATE_PROFILO_SEDI_TO_USER_PERMISSION',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per poter associarsi a una sede di una organizzazione nel profilo utente',
                'parent' => ['AMMINISTRATORE_ORGANIZZAZIONI', 'LETTORE_ORGANIZZAZIONI']
            ]
        ];
    }
}
