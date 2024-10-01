<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\documenti\models\Documenti;

/**
 * Class m171114_133632_documents_remove_workflow_active_lettore
 */
class m171114_133632_documents_remove_workflow_active_lettore extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO,
                'update' => true,
                'newValues' => [
                    'removeParents' => ['LETTORE_DOCUMENTI']
                ]
            ]
        ];
    }
}
