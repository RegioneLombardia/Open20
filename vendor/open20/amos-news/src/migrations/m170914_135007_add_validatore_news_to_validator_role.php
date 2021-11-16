<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;

/**
 * Class m170914_135007_add_validatore_news_to_validator_role
 */
class m170914_135007_add_validatore_news_to_validator_role extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'VALIDATORE_NEWS',
                'update' => true,
                'newValues' => [
                    'addParents' => ['VALIDATOR']
                ]
            ]
        ];
    }
}
