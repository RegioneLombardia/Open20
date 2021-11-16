<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\discussioni\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;

/**
 * Class m170914_135038_add_validatore_discussioni_to_validator_role
 */
class m170914_135038_add_validatore_discussioni_to_validator_role extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'VALIDATORE_DISCUSSIONI',
                'update' => true,
                'newValues' => [
                    'addParents' => ['VALIDATOR']
                ]
            ]
        ];
    }
}
