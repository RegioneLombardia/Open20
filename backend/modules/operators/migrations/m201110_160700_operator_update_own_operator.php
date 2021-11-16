<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use backend\modules\operators\rules\CreateOperatorRule;
use yii\rbac\Permission;


/**
 * Class m200828_161240_operator_update_permissions*/
class m201110_160700_operator_update_own_operator extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [

            [
                'name' =>\backend\modules\operators\rules\UpdateOwnOperatorRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'RULE for CREATE Operatore',
                'ruleName' => \backend\modules\operators\rules\UpdateOwnOperatorRule::className(),
                'parent' => ['PC_BASIC_USER']
            ],
            [
                'name' => 'OPERATOR_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => [\backend\modules\operators\rules\UpdateOwnOperatorRule::className(),]
                ],
            ],
        ];
    }
}
