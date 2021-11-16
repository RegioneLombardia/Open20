<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use backend\modules\operators\rules\CreateOperatorRule;
use yii\rbac\Permission;


/**
 * Class m200828_161240_operator_update_permissions*/
class m200828_161240_operator_update_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => CreateOperatorRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'RULE for CREATE Operatore',
                'ruleName' => CreateOperatorRule::className(),
                'parent' => ['PC_STRUCTURE_ADMIN']
            ],
            [
                'name' => \backend\modules\operators\rules\DeleteOperatorRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'RULE for CREATE Operatore',
                'ruleName' => \backend\modules\operators\rules\DeleteOperatorRule::className(),
                'parent' => ['PC_STRUCTURE_ADMIN']
            ],
            [
                'name' => \backend\modules\operators\rules\ReadOperatorRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'RULE for CREATE Operatore',
                'ruleName' => \backend\modules\operators\rules\ReadOperatorRule::className(),
                'parent' => ['PC_STRUCTURE_ADMIN']
            ],
            [
                'name' =>\backend\modules\operators\rules\UpdateOperatorRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'RULE for CREATE Operatore',
                'ruleName' => \backend\modules\operators\rules\UpdateOperatorRule::className(),
                'parent' => ['PC_STRUCTURE_ADMIN']
            ],
            [
                'name' => 'OPERATOR_CREATE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['PC_STRUCTURE_ADMIN'],
                    'addParents' => [CreateOperatorRule::className(),]

                ],
            ],
            [
                'name' => 'OPERATOR_READ',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['PC_STRUCTURE_ADMIN'],
                    'addParents' => [\backend\modules\operators\rules\ReadOperatorRule::className(),]

                ],
            ],
            [
                'name' => 'OPERATOR_UPDATE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['PC_STRUCTURE_ADMIN'],
                    'addParents' => [\backend\modules\operators\rules\UpdateOperatorRule::className(),]

                ],
            ],
            [
                'name' => 'OPERATOR_DELETE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['PC_STRUCTURE_ADMIN'],
                    'addParents' => [\backend\modules\operators\rules\DeleteOperatorRule::className(),]

                ],
            ],
        ];
    }
}
