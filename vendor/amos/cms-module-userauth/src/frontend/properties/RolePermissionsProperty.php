<?php

namespace amos\userauth\frontend\properties;

use amos\userauth\frontend\Module;
use amos\userauth\frontend\utility\cmspageblock\CmsDataPageBlock;
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

class RolePermissionsProperty extends CheckboxProperty
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

//        $this->on(self::EVENT_BEFORE_RENDER, [$this, 'ensureUserAccess']);
    }


    /**
     * @inheritdoc
     */
    public function varName()
    {
        return 'rolePermissions';
    }

    public function type() {
        return self::TYPE_LIST_ARRAY;
    }

    /**
     * @inheritdoc
     */
    public function label()
    {
        return Module::t('#role_permissions_propety_label');
    }

    /**
     * @return mixed
     */
    public function listValues(){
        $encodedValue = $this->value;
        $values = [];
        $valuesDecoded = json_decode($encodedValue);
        foreach ($valuesDecoded as $val){
            $values [] = $val->value;
        }

        return $values;
    }

    /**
     * @return bool
     */
    public function checkPermissions(){
        $values = $this->listValues();
        if(!\Yii::$app->user->isGuest) {
            foreach ($values as $permission) {
                $can = \Yii::$app->user->can($permission);
                if ($can) {
                    return true;
                }
            }
        }
        return false;
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