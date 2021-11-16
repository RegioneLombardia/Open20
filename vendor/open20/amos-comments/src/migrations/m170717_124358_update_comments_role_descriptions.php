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
 * Class m170717_124358_update_comments_role_descriptions
 */
class m170717_124358_update_comments_role_descriptions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'COMMENTS_ADMINISTRATOR',
                'update' => true,
                'newValues' => [
                    'description' => 'Administrator role for comments plugin',
                ],
                'oldValues' => [
                    'description' => 'Administrator role for events plugin',
                ]
            ],
            [
                'name' => 'COMMENTS_CONTRIBUTOR',
                'update' => true,
                'newValues' => [
                    'description' => 'Comments contributor role for comments plugin',
                ],
                'oldValues' => [
                    'description' => 'Comments validator role for events plugin',
                ]
            ]
        ];
    }
}