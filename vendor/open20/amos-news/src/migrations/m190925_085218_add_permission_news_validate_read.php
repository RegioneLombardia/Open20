<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;

/**
 * Class m190925_085218_add_permission_news_validate_read
 */
class m190925_085218_add_permission_news_validate_read extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'NEWS_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['NewsValidate'],
                ]
            ]
        ];
    }
}