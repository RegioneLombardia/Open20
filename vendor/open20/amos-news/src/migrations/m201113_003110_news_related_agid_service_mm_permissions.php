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
 * Class m201113_003110_news_related_agid_service_mm_permissions
 */
class m201113_003110_news_related_agid_service_mm_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'NEWSRELATEDAGIDSERVICEMM_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model NewsRelatedAgidServiceMm',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'NEWSRELATEDAGIDSERVICEMM_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model NewsRelatedAgidServiceMm',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'NEWSRELATEDAGIDSERVICEMM_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model NewsRelatedAgidServiceMm',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'NEWSRELATEDAGIDSERVICEMM_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model NewsRelatedAgidServiceMm',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ]
        ];
    }
}
