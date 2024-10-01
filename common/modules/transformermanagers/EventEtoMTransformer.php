<?php

namespace common\modules\transformermanagers;

use open20\elasticsearch\transformer\modeltransformers\BaseElasticToModelTRansformer;

class EventEtoMTransformer extends BaseElasticToModelTRansformer {
    /* @var $model ElasticIndex */

    public function transform() {
        $values = $this->elasticObject['_source'];
        $values['primo_piano'] = $values['public'];
        unset($values['public']);
        unset($values['tags']);
        unset($values['start_publication']);
        unset($values['end_publication']);

        $class = $this->getObjectClass();
        $model = new $class($values);
        $model->id = $this->elasticObject['_id'];
        return $model;
    }

}
