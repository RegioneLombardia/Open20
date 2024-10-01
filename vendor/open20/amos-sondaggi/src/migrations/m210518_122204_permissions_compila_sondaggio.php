<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\community\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m210518_122204_permissions_compila_sondaggio
 */
class m210518_122204_permissions_compila_sondaggio extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'COMPILA_SONDAGGIO',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Ruolo autorizzazione a compilare sondaggio',
                'ruleName' => null,
                'parent' => ['COMPILATORE_SONDAGGI']
            ],
        ];
    }
}
