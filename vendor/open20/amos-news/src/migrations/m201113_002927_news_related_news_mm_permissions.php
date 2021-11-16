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
 * Class m201113_002927_news_related_news_mm_permissions
 */
class m201113_002927_news_related_news_mm_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'NEWSRELATEDNEWSMM_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model NewsRelatedNewsMm',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'NEWSRELATEDNEWSMM_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model NewsRelatedNewsMm',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'NEWSRELATEDNEWSMM_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model NewsRelatedNewsMm',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'NEWSRELATEDNEWSMM_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model NewsRelatedNewsMm',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ]
        ];
    }
}
