<?php

namespace amos\userauth\frontend\properties;

use amos\userauth\frontend\Module;
use amos\userauth\frontend\utility\cmspageblock\CmsDataPageBlock;
use open20\amos\admin\models\UserProfileClassesAuthMm;
use open20\amos\community\utilities\CommunityUtil;
use luya\admin\base\CheckboxProperty;
use luya\admin\models\Config;
use luya\cms\frontend\events\BeforeRenderEvent;
use luya\cms\helpers\Url;
use luya\cms\menu\QueryOperatorFieldInterface;
use luya\cms\models\NavItem;
use mdm\admin\models\AuthItem;
use Yii;
use yii\helpers\ArrayHelper;

class RolePermissionsProperty extends CheckboxProperty {

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

        $this->on(self::EVENT_BEFORE_RENDER, [$this, 'checkPermissions']);
    }

    /**
     * @inheritdoc
     */
    public function varName() {
        return 'rolePermissions';
    }

    public function type() {
        return self::TYPE_LIST_ARRAY;
    }

    /**
     * @inheritdoc
     */
    public function label() {
        return Module::t('#role_permissions_propety_label');
    }

    /**
     * @return mixed
     */
    public function listValues($enableProfile) {
        $encodedValue = $this->value;
        $values = [];
        $valuesDecoded = json_decode($encodedValue);
        foreach ($valuesDecoded as $val) {
            $values [] = $val->value;
        }

        if($enableProfile) {
            $result = [];
            $profiles = $values;
            foreach($profiles as $profileId) {
                $permissions = UserProfileClassesAuthMm::find()
                    ->select('item_id')
                    ->andWhere(['user_profile_classes_id' => $profileId])
                    ->asArray()
                    ->all();
                $permissions = ArrayHelper::map($permissions, 'item_id', 'item_id');
                $result[] = $permissions;
            }
            $values = $result;
        }
        return $values;
    }

    /**
     * @param $event
     * @param bool $no_exception true se si sta controllando i permessi per aggiornare Elastic Search
     * @param bool $ret_permissions
     * @return array|bool|mixed
     * @throws \yii\web\ForbiddenHttpException
     */
    public function checkPermissions($event = null, $no_exception = false, $ret_permissions = false) {

        // Se l'utente è ADMIN può vedere la pagina a prescindere
        if(!$no_exception && \Yii::$app->user->can('ADMIN')) {
            return true;
        }

        $enableProfile = false;
        $moduleDashboard = \Yii::$app->getModule('dashboards');
        if (!empty($moduleDashboard)) {
            if ($moduleDashboard->hasProperty('enableProfile') && $moduleDashboard->enableProfile) {
                $enableProfile = true;
            }
        }

        $values = $this->listValues($enableProfile);
        if ($ret_permissions) {
            return $values;
        }
        if (empty($values)) {
            return true;
        }

        if ($enableProfile) {
            if (!$no_exception && !\Yii::$app->user->isGuest) {
                foreach ($values as $profiles) {
                    $canAccess = true;
                    foreach ($profiles as $permission) {
                        $can = \Yii::$app->user->can($permission);
                        if (!$can) {
                            $canAccess = false;
                            break;
                        }
                    }
                    if($canAccess) {
                        return true;
                    }
                }
                if($canAccess) {
                    return true;
                } else if ($no_exception) {
                    return false;
                } else {
                    throw new \yii\web\ForbiddenHttpException(\Yii::t('amosapp', "Access denied."), 403);
                }
            }
        } else {
            if (!$no_exception && !\Yii::$app->user->isGuest) {
                foreach ($values as $permission) {
                    $can = \Yii::$app->user->can($permission);
                    if ($can) {
                        return true;
                    }
                }
            }
        }
        if ($no_exception) {
            return false;
        }
        throw new \yii\web\ForbiddenHttpException(\Yii::t('amosapp', "Access denied."), 403);
    }

    /**
     * @return array|mixed
     */
//    public function options() {
//        $options = [];
//        $roles = \Yii::$app->authManager->getRoles();
//        foreach ($roles as $role){
//            $options []= ['value' => $role->name, 'label' => $role->description];
//        }
//       return $options;
//    }
}
