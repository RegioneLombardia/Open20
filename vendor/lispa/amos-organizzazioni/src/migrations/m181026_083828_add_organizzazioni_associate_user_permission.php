<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use lispa\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m181026_083828_add_organizzazioni_associate_user_permission
 */
class m181026_083828_add_organizzazioni_associate_user_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'ASSOCIATE_ORGANIZZAZIONI_TO_USER',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per associare un utente a una organizzazione nel profilo utente',
                'parent' => ['USERPROFILE_UPDATE']
            ]
        ];
    }
}
