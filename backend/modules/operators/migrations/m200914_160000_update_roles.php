<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use backend\modules\operators\rules\CreateOperatorRule;
use backend\modules\registeredusers\widgets\icons\WidgetRegisteredUser;
use yii\rbac\Permission;


/**
 * Class m200914_160000_update_roles*/
class m200914_160000_update_roles extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {

        return [
           
            [
                'name' => 'PC_LANDING_ADMINISTRATOR',
                'update' => true,
                'newValues' => [                    
                    'addParents' => ['PC_EDITOR', 'PC_STRUCTURE_EDITOR', 'PC_APPROVER', 'PC_STRUCTURE_APPROVER']

                ],
            ],

            [
                'name' => 'PC_STRUCTURE_APPROVER',
                'update' => true,
                'newValues' => [                    
                    'removeParents' => ['PC_STRUCTURE_EDITOR'],
                    
                ],
            ],

            [
                'name' => 'PC_STRUCTURE_EDITOR',
                'update' => true,
                'newValues' => [                    
                    'addParents' => ['PC_STRUCTURE_APPROVER']
                ],
            ],


            [
                'name' => 'PreferenceCampainChannelMmWorkflow/APPROVED',
                'update' => true,
                'newValues' => [                    
                    'addParents' => ['PC_STRUCTURE_APPROVER']
                ],
            ],

            [
                'name' => 'PreferenceCampainWorkflow/APPROVED',
                'update' => true,
                'newValues' => [                    
                    'addParents' => ['PC_STRUCTURE_APPROVER']
                ],
            ],
        ];
    }
}
