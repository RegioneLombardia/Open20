<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\base
 * @category   CategoryName
 */

namespace open20\elasticsearch\transformer\modeltransformers;

use open20\elasticsearch\Module;
use open20\elasticsearch\transformer\AbstractElasticToModelTransformer;
use open20\elasticsearch\transformer\AbstractTransformer;

class BaseElasticToModelTRansformer extends AbstractElasticToModelTransformer {

    protected $elasticObject;

    public function setElasticObject($elasticObject) {
        $this->elasticObject = $elasticObject;
        return $this;
    }
    
    public function getElasticObject() {
        return $this->elasticObject;
    }

    
    public function dropValues() {
        $values = $this->elasticObject['_source'];

        $module = Module::instance();
        if ($module->enableCwh) {
            unset($values[AbstractTransformer::NETWORKS_INDEX]);
            unset($values[AbstractTransformer::TAGS_INDEX]);
            unset($values[AbstractTransformer::STATUS_INDEX]);
        }
        $this->elasticObject['_source'] = $values;
        return $this;
    }

}
