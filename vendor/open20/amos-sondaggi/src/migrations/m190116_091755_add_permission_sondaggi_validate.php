<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

class m190116_091755_add_permission_sondaggi_validate extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'SONDAGGI_VALIDATOR',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to validate a news with cwh query',
                'parent' => ['AMMINISTRAZIONE_SONDAGGI']
            ],
            [
                'name' => 'SondaggiValidate',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to validate a news with cwh query',
                'ruleName' => \open20\amos\core\rules\ValidatorUpdateContentRule::className(),
                'parent' => ['VALIDATED_BASIC_USER','SONDAGGI_VALIDATOR'],
                'children' => [
                    'SONDAGGI_UPDATE',
                    'SONDAGGI_CREATE',
                    'SONDAGGIDOMANDE_CREATE',
                    'SONDAGGIDOMANDE_UPDATE',
                    'SONDAGGIDOMANDEPAGINE_CREATE',
                    'SONDAGGIDOMANDEPAGINE_UPDATE',
                    'open20\amos\sondaggi\widgets\icons\WidgetIconSondaggi',
                    'open20\amos\sondaggi\widgets\icons\WidgetIconPubblicaSondaggi',
                    'SondaggiWorkflow/VALIDATO',
                    'SondaggiWorkflow/BOZZA'
                ],
            ],
        ];
    }
}
