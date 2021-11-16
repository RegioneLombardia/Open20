<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;

/**
 * Class m170706_085404_associate_update_own_user_profile_with_basic_user
 */
class m170706_085404_associate_update_own_user_profile_with_basic_user extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'UpdateOwnUserProfile',
                'update' => true,
                'newValues' => [
                    'addParents' => ['BASIC_USER']
                ]
            ]
        ];
    }
}
