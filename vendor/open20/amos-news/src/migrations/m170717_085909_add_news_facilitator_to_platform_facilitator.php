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
 * Class m170717_085909_add_news_facilitator_to_platform_facilitator
 */
class m170717_085909_add_news_facilitator_to_platform_facilitator extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'FACILITATORE_NEWS',
                'update' => true,
                'newValues' => [
                    'addParents' => ['FACILITATOR']
                ]
            ],
        ];
    }
}
