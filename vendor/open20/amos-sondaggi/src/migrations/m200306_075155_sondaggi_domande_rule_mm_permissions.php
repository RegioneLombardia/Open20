<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m200306_075155_sondaggi_domande_rule_mm_permissions
 */
class m200306_075155_sondaggi_domande_rule_mm_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'SONDAGGIDOMANDERULEMM_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model SondaggiDomandeRuleMm',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'SONDAGGIDOMANDERULEMM_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model SondaggiDomandeRuleMm',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'SONDAGGIDOMANDERULEMM_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model SondaggiDomandeRuleMm',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'SONDAGGIDOMANDERULEMM_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model SondaggiDomandeRuleMm',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
        ];
    }
}