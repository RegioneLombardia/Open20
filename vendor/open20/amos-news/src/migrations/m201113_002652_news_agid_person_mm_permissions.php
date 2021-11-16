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
 * Class m201113_002652_news_agid_person_mm_permissions
 */
class m201113_002652_news_agid_person_mm_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'NEWSAGIDPERSONMM_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model NewsAgidPersonMm',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'NEWSAGIDPERSONMM_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model NewsAgidPersonMm',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'NEWSAGIDPERSONMM_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model NewsAgidPersonMm',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'NEWSAGIDPERSONMM_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model NewsAgidPersonMm',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ]
        ];
    }
}
