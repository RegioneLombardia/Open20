<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m210519_071225_rule_generic_permission
 */
class m210519_071225_rule_generic_permission extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'SondaggiSessioniWorkflowGeneric',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to workflow of Sondaggi Risposte Sessioni',
                'ruleName' => \open20\amos\sondaggi\rules\SondaggiSessioniWorkflowGeneric::class,
                'parent' => ['COMPILA_SONDAGGIO']
            ],
        ];
    }
}