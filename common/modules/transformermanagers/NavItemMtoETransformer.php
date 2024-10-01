<?php

namespace common\modules\transformermanagers;

use open20\elasticsearch\models\NavItem;
use open20\elasticsearch\transformer\AbstractTransformer;
use open20\elasticsearch\transformer\modeltransformers\BaseModelToElasticTransformer;

class NavItemMtoETransformer extends BaseModelToElasticTransformer {
    /* @var $model NavItem */

    public function transform() {
        $model = $this->getModel();
        $values['elasticSourceText'] = ElasticModelSearch::purifyText($model->elasticSourceText);//$this->filterString($model->elasticSourceText);
        $values['elasticUrl'] = $this->filterString($model->elasticUrl);
        $values['title'] = $this->filterString($model->title);
        $values['public'] = 1 - $this->isPageProtected($model->nav);//NavItem::findOne($model->id)->id;//nav->getProperty("userAuthProtection")->value;
        $values[AbstractTransformer::STATUS_INDEX] = $this->isPageValidated($model->nav);//AbstractTransformer::VALIDATED_VALUE;
        $values['start_publication'] = date("c", $model->timestamp_create);
        $values['end_publication'] = date("c", strtotime(static::MAX_DATE));
        return $values;
    }

    public function isPageProtected($nav) {
        $secure = $nav->is_offline;
        $secure2 = false;
        $prop = $nav->getProperty("userAuthProtection");
        $prop2 = $nav->getProperty("rolePermissions");
        if ($prop !== false) {
            /** @var luya\admin\base\Property $prop */
            //$secure = $prop->value == '1' ? 0 : 1;
            $secure = $nav->is_offline || $prop->value;
        }
        if($prop2 !== false){            
            $secure2 = !$prop2->checkPermissions(null, true);
        }
        return ($secure || $secure2);
    }
        

    public function isPageValidated($nav) {
        $secure = $this->isPageProtected($nav);
        if ($secure) {
            $secure = AbstractTransformer::DRAFT_VALUE;
        } else {
            $secure = AbstractTransformer::VALIDATED_VALUE;
        }
        return $secure;
    }
}