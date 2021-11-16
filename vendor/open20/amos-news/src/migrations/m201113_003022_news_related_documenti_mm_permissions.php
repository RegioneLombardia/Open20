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
 * Class m201113_003022_news_related_documenti_mm_permissions
 */
class m201113_003022_news_related_documenti_mm_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'NEWSRELATEDDOCUMENTIMM_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model NewsRelatedDocumentiMm',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'NEWSRELATEDDOCUMENTIMM_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model NewsRelatedDocumentiMm',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'NEWSRELATEDDOCUMENTIMM_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model NewsRelatedDocumentiMm',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'NEWSRELATEDDOCUMENTIMM_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model NewsRelatedDocumentiMm',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ]
        ];
    }
}
