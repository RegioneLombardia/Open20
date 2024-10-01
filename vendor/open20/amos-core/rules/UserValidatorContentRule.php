<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\core\rules
 * @category   CategoryName
 */

namespace open20\amos\core\rules;

use open20\amos\core\controllers\BaseController;
use open20\amos\core\record\Record;
use open20\amos\cwh\models\CwhConfig;
use yii\base\InvalidConfigException;

/**
 * Class UserValidatorContentRule
 * @package open20\amos\core\rules
 */
class UserValidatorContentRule extends DefaultOwnContentRule {

    /**
     * @inheritdoc
     */
    public $name = 'userValidatorContent';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params) {
        $modelClassName = '';
        $cwhModule = \Yii::$app->getModule('cwh');
        if (isset($params['model'])) {
            /** @var Record $model */
            $model = $params['model'];
            $modelClassName = $model->className();

            if (!$model->id) {
                $post = \Yii::$app->getRequest()->post();
                $get = \Yii::$app->getRequest()->get();
                if (isset($get['id'])) {
                    $model = $this->instanceModel($model, $get['id']);
                } elseif (isset($post['id'])) {
                    $model = $this->instanceModel($model, $post['id']);
                }
            }
        } else {
            $controller = \Yii::$app->controller;
            if ($controller instanceof BaseController) {
                $modelClassName = $controller->getModelClassName();
            }
        }
        if (!isset($cwhModule) || !in_array($modelClassName, $cwhModule->modelsEnabled)) {
            return false;
        } else {
            $permissionCwhValidate = $cwhModule->permissionPrefix . "_VALIDATE_" . $modelClassName;
            return $this->userValidatorContentPermission($user, $permissionCwhValidate, $model);
        }
    }

    /**
     * @param int $userId
     * @param string $permissionCwhValidate
     * @return bool
     * @throws InvalidConfigException
     */
    private function userValidatorContentPermission($userId, $permissionCwhValidate, $model = null) {
        $moduleCwh = \Yii::$app->getModule('cwh');
        if (!empty($moduleCwh)) {
            $scope = $moduleCwh->getCwhScope();
            if (empty($scope)) {
                $scope = $model->destinatari;
            }
            if (!empty($scope)) {
                foreach ($scope as $network => $id) {
                    if (strpos($network, '-') !== false) {
                        $network = strtok($network, '-');
                        if (strpos($id, '-') !== false) {
                            $id = str_replace($network . '-', '', $id);
                        }
                    }
                    $cwhConfigId = CwhConfig::findOne(['tablename' => $network])->id;
                    $cwhNetworkId = $id;
                }
                if (!empty($cwhConfigId) && !empty($cwhNetworkId)) {
                    $cwhContentValidatePerssions = \open20\amos\cwh\models\base\CwhAuthAssignment::find()
                            ->andWhere(['cwh_config_id' => $cwhConfigId])
                            ->andWhere(['cwh_network_id' => $cwhNetworkId])
                            ->andWhere(['user_id' => $userId, 'item_name' => $permissionCwhValidate])
                            ->count();
                }
            }
        }

        return (!empty($cwhContentValidatePerssions));
    }
}
