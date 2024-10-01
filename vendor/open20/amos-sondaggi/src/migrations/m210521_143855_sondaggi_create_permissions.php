<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m200306_075155_sondaggi_domande_rule_mm_permissions
 */
class m210521_143855_sondaggi_create_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'SondaggiValidate',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['COMPILATORE_SONDAGGI']
                ]
            ],
        ];
    }
}