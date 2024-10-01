<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;

/**
 * Class m170914_135049_add_validatore_documenti_to_validator_role
 */
class m170914_135049_add_validatore_documenti_to_validator_role extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'VALIDATORE_DOCUMENTI',
                'update' => true,
                'newValues' => [
                    'addParents' => ['VALIDATOR']
                ]
            ]
        ];
    }
}
