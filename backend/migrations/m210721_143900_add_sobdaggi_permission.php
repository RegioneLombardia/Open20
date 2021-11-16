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

class m210721_143900_add_sobdaggi_permission extends AmosMigrationPermissions {

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
                'name' => 'AMMINISTRAZIONE_SONDAGGI',
                'update' => true,
                'newValues' => [
                    'addParents' => ['PC_BASIC_USER'],
                ]
            ],

        ];
    }

}
