<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use backend\modules\operators\rules\CreateOperatorRule;
use backend\modules\registeredusers\widgets\icons\WidgetRegisteredUser;
use yii\rbac\Permission;


/**
 * Class m200914_165000_update_roles */
class m200914_165000_update_roles extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {

        return [
    
            [
                'name' => WidgetRegisteredUser::className(),
                'update' => true,
                'newValues' => [                    
                    'removeParents' => ['PC_STRUCTURE_ADMIN', 'PC_STRUCTURE_EDITOR'],
                ],
            ],  

        ];
    }
}
