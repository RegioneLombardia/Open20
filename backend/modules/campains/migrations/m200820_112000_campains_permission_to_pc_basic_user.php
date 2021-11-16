<?php

use backend\modules\campains\rules\CreateCampainRule;
use backend\modules\campains\rules\DeleteCampainRule;
use backend\modules\campains\rules\ReadCampainRule;
use backend\modules\campains\rules\UpdateCampainRule;
use backend\modules\campains\rules\ViewCampainRule;
use backend\modules\campains\widgets\icons\WidgetCampains;
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;

class m200820_112000_campains_permission_to_pc_basic_user extends AmosMigrationPermissions {

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations() {
        return $this->setMyPermissions();
    }

    /**
     * Plugin widgets permissions
     *
     * @return array
     */
    private function setMyPermissions() {
        return [
            [
                'name' => CreateCampainRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'RULE for CREATE Campain',
                'ruleName' => CreateCampainRule::className(),
                'parent' => ['PC_BASIC_USER']
            ],
            [
                'name' => 'PREFERENCECAMPAIN_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => [CreateCampainRule::className()],
                ]
            ],

            [
                'name' => ReadCampainRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'RULE for VIEW Campain',
                'ruleName' => ReadCampainRule::className(),
                'parent' => ['PC_BASIC_USER']
            ],
            [
                'name' => 'PREFERENCECAMPAIN_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => [ReadCampainRule::className()],
                ]
            ],

            [
                'name' => UpdateCampainRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'RULE for UPDATE Campain',
                'ruleName' => UpdateCampainRule::className(),
                'parent' => ['PC_BASIC_USER']
            ],
            [
                'name' => 'PREFERENCECAMPAIN_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => [UpdateCampainRule::className()],
                ]
            ],

            [
                'name' => DeleteCampainRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'RULE for DELETE Campain',
                'ruleName' => DeleteCampainRule::className(),
                'parent' => ['PC_BASIC_USER']
            ],
            [
                'name' => 'PREFERENCECAMPAIN_DELETE',
                'update' => true,
                'newValues' => [
                    'addParents' => [DeleteCampainRule::className()],
                ]
            ],
            
        ];
    }

}
