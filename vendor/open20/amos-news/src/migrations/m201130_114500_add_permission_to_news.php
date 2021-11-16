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
 * Class m201130_114500_add_permission_to_news
 */
class m201130_114500_add_permission_to_news extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'NEWS_PUBLISHER_FRONTEND',
                'update' => true,
                'newValues' => [
                    'addParents' => ['ADMIN']
                ]
            ]
        ];
    }
}
