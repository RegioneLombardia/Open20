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
use yii\rbac\Permission;

/**
 * Class m170328_084909_add_documents_permissions
 */
class m170328_084909_add_documents_permissions extends AmosMigrationPermissions
{
    protected function setAuthorizations()
    {
        $this->authorizations = [
            [
                'name' => 'AMMINISTRATORE_DOCUMENTI',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Amministratore documenti',
                'ruleName' => null,
                'parent' => ['ADMIN'],
                'dontRemove' => true
            ],
        ];
    }
}
