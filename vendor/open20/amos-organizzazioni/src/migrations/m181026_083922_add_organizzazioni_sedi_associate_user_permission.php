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
 * Class m181026_083922_add_organizzazioni_sedi_associate_user_permission
 */
class m181026_083922_add_organizzazioni_sedi_associate_user_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'ASSOCIATE_ORGANIZZAZIONI_SEDI_TO_USER',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per associare un utente a una sede di una organizzazione nel profilo utente',
                'parent' => ['USERPROFILE_UPDATE']
            ]
        ];
    }
}
