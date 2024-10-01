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

class MenuReadonlyProperty extends CheckboxProperty
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
        return 'menuReadonly';
    }

    public function type() {
        return self::TYPE_CHECKBOX;
    }

    /**
     * @inheritdoc
     */
    public function label()
    {
        return Module::t('#menu_readonly_propety_label');
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