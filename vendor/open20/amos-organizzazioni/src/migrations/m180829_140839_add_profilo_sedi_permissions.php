<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\organizzazioni\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m180829_140839_add_profilo_sedi_permissions
 */
class m180829_140839_add_profilo_sedi_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'PROFILOSEDI_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model ProfiloSedi',
                'parent' => ['AMMINISTRATORE_ORGANIZZAZIONI']
            ],
            [
                'name' => 'PROFILOSEDI_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model ProfiloSedi',
                'parent' => ['AMMINISTRATORE_ORGANIZZAZIONI']
            ],
            [
                'name' => 'PROFILOSEDI_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model ProfiloSedi',
                'parent' => ['AMMINISTRATORE_ORGANIZZAZIONI']
            ],
            [
                'name' => 'PROFILOSEDI_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model ProfiloSedi',
                'parent' => ['AMMINISTRATORE_ORGANIZZAZIONI']
            ],
        ];
    }
}
