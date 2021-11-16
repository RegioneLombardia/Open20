<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;

/**
 * Class m170829_154009_comments_contributor_to_basic_user
 */
class m170829_154009_comments_contributor_to_basic_user extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'COMMENTS_CONTRIBUTOR',
                'update' => true,
                'newValues' => [
                    'addParents' => ['BASIC_USER'],
                    'removeParents' => ['VALIDATED_BASIC_USER']
                ]
            ]
        ];
    }
}
