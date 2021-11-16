<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;

/**
 * Class m170914_135116_add_validatore_community_to_validator_role
 */
class m170914_135116_add_validatore_community_to_validator_role extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'COMMUNITY_VALIDATOR',
                'update' => true,
                'newValues' => [
                    'addParents' => ['VALIDATOR']
                ]
            ]
        ];
    }
}
