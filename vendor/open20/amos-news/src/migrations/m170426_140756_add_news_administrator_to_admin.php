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
 * Class m170426_140756_add_news_administrator_to_admin
 */
class m170426_140756_add_news_administrator_to_admin extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'AMMINISTRATORE_NEWS',
                'update' => true,
                'newValues' => [
                    'addParents' => ['ADMIN']
                ]
            ]
        ];
    }
}
