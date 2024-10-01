<?php


/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\collaborations\migrations
 */

use open20\amos\core\migration\AmosMigrationPermissions;

/**
 * Class m181031_150000_modify_ticket_permissions
 */
class m221219_165024_update_expression_of_interest_model_permissions
    extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'COLLABORATIONS_STAFF',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        'BASIC_USER',
                    ]
                ]
            ],
        ];
    }
}
