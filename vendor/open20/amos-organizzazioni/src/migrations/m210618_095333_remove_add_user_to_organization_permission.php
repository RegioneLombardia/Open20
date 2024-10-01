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


/**
 * Class m210618_095333_remove_add_user_to_organization_permission
 */
class m210618_095333_remove_add_user_to_organization_permission extends AmosMigrationPermissions
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
                    'removeParents' => ['USERPROFILE_UPDATE']
                ]
            ]
        ];
    }
}
