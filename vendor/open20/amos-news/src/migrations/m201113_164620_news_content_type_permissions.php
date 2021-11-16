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
use yii\rbac\Permission;

/**
 * Class m201113_164620_news_content_type_permissions
 */
class m201113_164620_news_content_type_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'NEWSCONTENTTYPE_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model NewsContentType',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'NEWSCONTENTTYPE_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model NewsContentType',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'NEWSCONTENTTYPE_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model NewsContentType',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'NEWSCONTENTTYPE_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model NewsContentType',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ]
        ];
    }
}
