<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\cwh
 * @category   CategoryName
 */
use yii\db\Migration;

class m160908_141702_add_cwh_rules extends Migration {

    public $rules = [
        [
            'label' => 'Create',
            'name' => 'CREATE'
        ],
        [
            'label' => 'Validate',
            'name' => 'VALIDATE'
        ],
    ];

    public function safeUp() {
        $cwhModule = Yii::$app->getModule('cwh');

        if (!$cwhModule || !count(Yii::$app->getModule('cwh')->modelsEnabled)) {
            //throw new \yii\base\InvalidConfigException(\open20\amos\cwh\AmosCwh::t('amoscwh', 'Impossibile configurare le regole della CWH : modelsEnabled deve essere valorizzato'));
        }

        $auth = \Yii::$app->getAuthManager();
        if (!empty($cwhModule)) {
            foreach ((array) $cwhModule->modelsEnabled as $model) {
                foreach ($this->rules as $rule) {
                    $permissionName = $cwhModule->permissionPrefix . "_" . $rule['name'] . "_" . $model;
                    if (is_null($auth->getPermission($permissionName))) {
                        echo "\nCreating cwh rule " . $permissionName;
                        $permissionCwhModel = $auth->createPermission($permissionName);
                        $permissionCwhModel->description = "{$rule['label']} {$model}";

                        $auth->add($permissionCwhModel);
                    } else {
                        echo "\nAlready exists cwh rule " . $permissionName;
                    }
                }
            }
        }
        echo "\n";
        return true;
    }

    public function safeDown() {
        return true;
    }

}
